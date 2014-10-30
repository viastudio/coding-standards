<?php
/**
 * Ensure there's no white space PHP tags on its own line.
 *
 * PHP version 5
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

/**
 * Ensure there's no white space PHP tags on its own line.
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
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
            $trim_len = -1;
        } else {
            $trim_len = strlen(trim($tokens[$stackPtr - 1]['content']));
        }


        if ($tokens[$stackPtr]['code'] === T_CLOSE_TAG) {
            if ($tokens[$stackPtr - 1]['line'] !== $tokens[$stackPtr]['line']) {
                return;
            }

            $lastContent = $phpcsFile->findFirstOnLine(T_OPEN_TAG, $stackPtr);

            if ($lastContent === false && $trim_len === 0) {
                $error = 'Closing PHP tag must not be indented when on its own line';
                $phpcsFile->addError($error, $stackPtr, 'CloseTag', array());

                return;
            }
        } elseif ($tokens[$stackPtr]['code'] === T_OPEN_TAG) {
            if ($trim_len === 0 && $tokens[$stackPtr - 1]['line'] === $tokens[$stackPtr]['line']) {
                $error = 'Opening PHP tag must not be indented when on its own line';
                $phpcsFile->addError($error, $stackPtr, 'OpenTag', array());

                return;
            }

            $closeTagPtr = $phpcsFile->findNext(T_CLOSE_TAG, $stackPtr);
            if ($closeTagPtr !== false && $tokens[$closeTagPtr]['line'] === $tokens[$stackPtr]['line']) {
                return;
            }

            if (isset($tokens[$stackPtr + 1]) && $tokens[$stackPtr + 1]['line'] === $tokens[$stackPtr]['line']) {
                $error = 'Can not have code on same line as PHP open tag';
                $phpcsFile->addError($error, $stackPtr, 'SameLine', array());

                return;
            }

            $closeTagPtr = $phpcsFile->findPrevious(T_CLOSE_TAG, $stackPtr);
            if ($closeTagPtr === false) {
                return;
            }

            if ($closeTagPtr === ($stackPtr - 1)) {
                $error = 'PHP close tag immediately followed by PHP open tag';
                $phpcsFile->addError($error, $stackPtr, 'Condense', array());

                return;
            }
        }

        if ($tokens[$stackPtr]['level'] !== 0) {
            if ($tokens[$stackPtr]['code'] === T_CLOSE_TAG) {
                $openTagPtr = $phpcsFile->findPrevious(T_OPEN_TAG, $stackPtr);

                if ($openTagPtr === false || $tokens[$openTagPtr]['line'] != $tokens[$stackPtr]['line']) {
                    return;
                }

                if (isset($tokens[$openTagPtr - 1]) && $tokens[$openTagPtr - 1]['line'] != $tokens[$openTagPtr]['line']) {
                    $error = 'Can not use inline PHP within indented block';
                    $phpcsFile->addError($error, $stackPtr, 'Inline', array());

                    return;
                }
            }
        }
    }
}
