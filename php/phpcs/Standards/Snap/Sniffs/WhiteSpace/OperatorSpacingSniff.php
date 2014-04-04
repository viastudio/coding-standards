<?php
/**
 * Snap_Sniffs_WhiteSpace_OperatorSpacingSniff
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Author Tags Are Dumb <authortagsdumb@richardhoward.net>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Snap_Sniffs_WhiteSpace_OperatorSpacingSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $comparison = PHP_CodeSniffer_Tokens::$comparisonTokens;
        $operators  = PHP_CodeSniffer_Tokens::$operators;
        $operators[] = T_STRING_CONCAT;
        $assignment = PHP_CodeSniffer_Tokens::$assignmentTokens;

        return array_unique(array_merge($comparison, $operators, $assignment));

    }//end register()


    /**
     * Processes this sniff when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being checked.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Skip default values in function declarations.
        if ($tokens[$stackPtr]['code'] === T_EQUAL
            || $tokens[$stackPtr]['code'] === T_MINUS
        ) {
            if (isset($tokens[$stackPtr]['nested_parenthesis']) === true) {
                $parenthesis = array_keys($tokens[$stackPtr]['nested_parenthesis']);
                $bracket     = array_pop($parenthesis);
                if (isset($tokens[$bracket]['parenthesis_owner']) === true) {
                    $function = $tokens[$bracket]['parenthesis_owner'];
                    if ($tokens[$function]['code'] === T_FUNCTION
                        || $tokens[$function]['code'] === T_CLOSURE
                    ) {
                        return;
                    }
                }
            }
        }

        if ($tokens[$stackPtr]['code'] === T_EQUAL) {
            // Skip for '=&' case.
            if (isset($tokens[($stackPtr + 1)]) === true && $tokens[($stackPtr + 1)]['code'] === T_BITWISE_AND) {
                return;
            }
        }

        if ($tokens[$stackPtr]['code'] === T_BITWISE_AND) {
            // If it's not a reference, then we expect one space either side of the
            // bitwise operator.
            if ($phpcsFile->isReference($stackPtr) === true) {
                return;
            }

            $found = $this->checkSpaceBefore($tokens, $stackPtr);
            if ($found !== 1) {
                $error = 'Expected 1 space before "&" operator; %s found';
                $data = array($found);
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeAmp', $data);
            }

            // Check there is one space after the & operator.
            if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
                $error = 'Expected 1 space after "&" operator; 0 found';
                $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterAmp');
            } else {
                if (strlen($tokens[($stackPtr + 1)]['content']) !== 1) {
                    $found = strlen($tokens[($stackPtr + 1)]['content']);
                    $error = 'Expected 1 space after "&" operator; %s found';
                    $data  = array($found);
                    $phpcsFile->addError($error, $stackPtr, 'SpacingAfterAmp', $data);
                }
            }

            return;
        }//end if

        if ($tokens[$stackPtr]['code'] === T_MINUS) {
            // Check minus spacing, but make sure we aren't just assigning
            // a minus value or returning one.
            $prev = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
            if ($tokens[$prev]['code'] === T_RETURN) {
                // Just returning a negative value; eg. (return -1).
                return;
            }

            if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$operators) === true) {
                // Just trying to operate on a negative value; eg. ($var * -1).
                return;
            }

            if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens) === true) {
                // Just trying to compare a negative value; eg. ($var === -1).
                return;
            }

            if (in_array($tokens[$prev]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens) === true) {
                // Just trying to assign a negative value; eg. ($var = -1).
                return;
            }

            // A list of tokens that indicate that the token is not
            // part of an arithmetic operation.
            $invalidTokens = array(
                              T_COMMA,
                              T_OPEN_PARENTHESIS,
                              T_OPEN_SQUARE_BRACKET,
                              T_DOUBLE_ARROW,
                              T_COLON,
                              T_INLINE_THEN,
                              T_INLINE_ELSE,
                              T_CASE,
                             );

            if (in_array($tokens[$prev]['code'], $invalidTokens) === true) {
                return;
            }
        }//end if

        $operator = $tokens[$stackPtr]['content'];
        $found = $this->checkSpaceBefore($tokens, $stackPtr);

        // Don't throw an error for assignments, because other standards allow
        // multiple spaces there to align multiple assignments.
        if ($found === 0 || (
            $found > 1
            && in_array($tokens[$stackPtr]['code'], PHP_CodeSniffer_Tokens::$assignmentTokens) === false
        )) {
            $error = 'Expected 1 space before "%s"; %s found';
            $data  = array(
                $operator,
                $found
            );
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceBefore', $data);
        }

        if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE) {
            $error = "Expected 1 space after \"$operator\"; 0 found";
            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfter');
        } else if (strlen($tokens[($stackPtr + 1)]['content']) !== 1) {
            $found = strlen($tokens[($stackPtr + 1)]['content']);
            $error = 'Expected 1 space after "%s"; %s found';
            $data  = array(
                      $operator,
                      $found,
                     );
            $phpcsFile->addError($error, $stackPtr, 'SpacingAfter', $data);
        }

    }//end process()


    /**
     * Check that there is a single space (or line break) immediately before the token.
     *
     * @param array $tokens    The tokens from the file being sniffed.
     * @param int   $stackPtr  The position of the current token in the stack 
     *                         passed in $tokens.
     *
     * @return int  1 if a single space or line break was found, otherwise the 
     *              number of spaces found.
     */
    private function checkSpaceBefore(array $tokens, $stackPtr)
    {
        $operator = $tokens[$stackPtr]['content'];
        $current_line = $tokens[$stackPtr]['line'];
        $count = 0;
        for ($i = 1; $tokens[($stackPtr - $i)]['code'] === T_WHITESPACE; $i++) {
            if ($tokens[($stackPtr - $i)]['line'] !== $current_line) {
                return 1;
            }
            $count += strlen($tokens[($stackPtr - $i)]['content']);
        }
        return $count;
    }//end checkSpaceBefore


}//end class

?>
