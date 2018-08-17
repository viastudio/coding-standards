<?php
/**
 * Closing tag is not allowed at end of a file,
 * unless it is an inline PHP block in an HTML file
 *
 * PHP version 5
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

namespace Via\Sniffs\Formatting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Closing tag is not allowed at end of a file,
 * unless it is an inline PHP block in an HTML file
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class OpenTagSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register() {
        return array(T_OPEN_TAG);
    }


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        $closeTagPtr = $phpcsFile->findPrevious(T_CLOSE_TAG, $stackPtr);
        if ($closeTagPtr === false) {
            // no previous closing tag
            return;
        }

        $htmlTagPtr = $phpcsFile->findPrevious(T_INLINE_HTML, ($closeTagPtr - 1));
        if ($htmlTagPtr !== false && $tokens[$closeTagPtr]['line'] === $tokens[$htmlTagPtr]['line']) {
            // previous tag block is inline with HTML
            return;
        }

        $content = '';
        for ($i = ($closeTagPtr + 1); $i < $stackPtr; ++$i) {
            $content .= $tokens[$i]['content'];
        }

        $content = trim($content);

        if (empty($content)) {
            $error = 'PHP close tag immediately followed by PHP open tag';
            $phpcsFile->addError($error, $stackPtr, 'Condense', array());
        }
    }
}
