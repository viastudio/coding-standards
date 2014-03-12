<?php
/**
 * Class Declaration Test.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Class Declaration Test.
 *
 * Checks the declaration of the class is correct.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Via_Sniffs_Classes_ClassDeclarationSniff implements PHP_CodeSniffer_Sniff {
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_CLASS, T_INTERFACE, T_TRAIT);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param integer              $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
        $tokens    = $phpcsFile->getTokens();
        $errorData = array(strtolower($tokens[$stackPtr]['content']));

        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            $error = 'Possible parse error: %s missing opening or closing brace';
            $phpcsFile->addWarning($error, $stackPtr, 'MissingBrace', $errorData);
            return;
        }

        $curlyBrace  = $tokens[$stackPtr]['scope_opener'];
        $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($curlyBrace - 1), $stackPtr, true);

        if ($tokens[$lastContent]['line'] !== $tokens[$curlyBrace]['line']) {
            $error = 'Opening brace of a %s must be on the same line with the definition';
            $phpcsFile->addError($error, $curlyBrace, 'OpenBraceSameLine', $errorData);
            return;
        }

        $spaces = 0;
        if ($tokens[($curlyBrace - 1)]['code'] === T_WHITESPACE) {
            $spaces = strlen($tokens[($curlyBrace - 1)]['content']);
        }

        if ($spaces !== 1) {
            $error = 'There must be a single space between the closing parenthesis and the opening brace of a class declaration; %s found';
            $data  = array($spaces);
            $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeOpenBrace', $data);
        }

        if ($tokens[($curlyBrace + 1)]['content'] !== $phpcsFile->eolChar) {
            $error = 'Opening brace in class declaration must be the last content on a line';
            $phpcsFile->addError($error, $curlyBrace, 'NothingAfterOpenBrace', $errorData);
        }
    }
}
