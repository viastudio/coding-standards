<?php
/**
 * Via_Sniffs_Formatting_ClosingTagsSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Jason McCreary <jmac@viastudio.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/viastudio/coding-standards
 */

/**
 * Via_Sniffs_Formatting_LineEndingsSniff.
 *
 * Checks that the file does not end with a closing tag.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Jason McCreary <jmac@viastudio.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      https://github.com/viastudio/coding-standards
 */
class Via_Sniffs_Formatting_ClosingTagSniff implements PHP_CodeSniffer_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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