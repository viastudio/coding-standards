<?php
/**
 * Ensure there's no white space PHP tags on its own line.
 *
 * PHP version 5
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

namespace Via\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Ensure there's no white space PHP tags on its own line.
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class PHPTagSniff implements Sniff {
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
     * @param File $phpcsFile The file being scanned.
     * @param integer              $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
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
