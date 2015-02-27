<?php
/**
 * Via_Sniffs_PHP_DisallowMultipleAssignmentsSniff.
 *
 * PHP version 5
 *
 * @author    Joel Jacob <joel@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

if (class_exists('Squiz_Sniffs_PHP_DisallowMultipleAssignmentsSniff', true) === false) {
    $error = 'Class Squiz_Sniffs_PHP_DisallowMultipleAssignmentsSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}


/**
 * Via_Sniffs_PHP_DisallowMultipleAssignmentsSniff.
 *
 * @author    Joel Jacob <joel@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class Via_Sniffs_PHP_DisallowMultipleAssignmentsSniff extends Squiz_Sniffs_PHP_DisallowMultipleAssignmentsSniff {


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_EQUAL);

    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        // Ignore assignments in control structures.
        $function = $phpcsFile->findPrevious(array(T_WHILE, T_FOR, T_IF, T_ELSEIF), ($stackPtr - 1), null, false, null, true);
        if ($function !== false) {
            $opener = $tokens[$function]['parenthesis_opener'];
            $closer = $tokens[$function]['parenthesis_closer'];
            if ($opener < $stackPtr && $closer > $stackPtr) {
                return;
            }
        }

        return parent::process($phpcsFile, $stackPtr);

    }


}
