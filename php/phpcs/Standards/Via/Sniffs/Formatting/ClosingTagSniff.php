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
class ClosingTagSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLOSE_TAG);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $next = $phpcsFile->findNext(T_INLINE_HTML, ($stackPtr + 1), null, true);
        if ($next !== false) {
            return;
        }

        $has_html = $phpcsFile->findPrevious(T_INLINE_HTML, ($stackPtr - 1));
        $prev = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($has_html !== false && $prev !== false && $tokens[$prev]['line'] === $tokens[$stackPtr]['line']) {
            // allow closing tag for single line PHP blocks in HTML files
            return;
        }

        // We've found the last closing tag in the file so the only thing
        // potentially remaining is inline HTML. Now we need to figure out
        // whether or not it's just a bunch of whitespace.
        $content = '';
        for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
            $content .= $tokens[$i]['content'];
        }

        // Check if the remaining inline HTML is just whitespace.
        $content = trim($content);
        if (empty($content)) {
            $error = 'A closing tag is not permitted at the end of a PHP file';
            $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
        }

    }//end process()


}//end class

?>