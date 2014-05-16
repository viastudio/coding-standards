<?php
/**
 * Via_Sniffs_WhiteSpace_PHPTagSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Via_Sniffs_WhiteSpace_PHPTagSniff.
 *
 * Ensure there's no white space before the PHP open tag when on its own line.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 1.5.2
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Via_Sniffs_WhiteSpace_PHPTagSniff implements PHP_CodeSniffer_Sniff {
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_OPEN_TAG,T_CLOSE_TAG);
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
        $tokens = $phpcsFile->getTokens();

        if ($stackPtr === 0) {
            return;
        }

        if ($tokens[$stackPtr - 1]['line'] !== $tokens[$stackPtr]['line']) {
            return;
        }

        $previous_content = $tokens[$stackPtr - 1]['content'];
        $trim_len = strlen(trim($previous_content));

        if ($tokens[$stackPtr]['code'] === T_CLOSE_TAG) {
            $lastContent = $phpcsFile->findFirstOnLine(T_OPEN_TAG, $stackPtr);
            if (!$lastContent && $trim_len === 0) {
                $error = 'Closing PHP tag must not be indented when on its own line';
                $phpcsFile->addError($error, $stackPtr, 'CloseTag', array());
                return;
            }
        }
        elseif ($tokens[$stackPtr]['code'] === T_OPEN_TAG && $trim_len === 0) {
            $error = 'Opening PHP tag must not be indented when on its own line';
            $phpcsFile->addError($error, $stackPtr, 'OpenTag', array());
        }
    }
}