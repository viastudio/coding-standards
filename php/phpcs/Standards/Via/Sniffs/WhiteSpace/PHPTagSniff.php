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
            return;
        }

        $tokens_before = isset($tokens[$stackPtr - 1]) && $tokens[$stackPtr - 1]['line'] === $tokens[$stackPtr]['line'];
        $tokens_after = isset($tokens[$stackPtr + 1]) && $tokens[$stackPtr + 1]['line'] === $tokens[$stackPtr]['line'];

        if ($tokens[$stackPtr]['level'] !== 0 && (($tokens[$stackPtr]['code'] === T_CLOSE_TAG && $tokens_before) || ($tokens[$stackPtr]['code'] === T_OPEN_TAG && $tokens_after))) {
            $error = 'Can not use inline PHP within indented block';
            $phpcsFile->addError($error, $stackPtr, 'SameLine', array());

            return;
        }

        if (!$tokens_before) {
            return;
        }

        $previous_content = $tokens[$stackPtr - 1]['content'];
        $trim_len = strlen(trim($previous_content));

        if ($tokens[$stackPtr]['code'] === T_CLOSE_TAG) {
            $lastContent = $phpcsFile->findFirstOnLine(T_OPEN_TAG, $stackPtr);

            if ($lastContent === false && $trim_len === 0) {
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