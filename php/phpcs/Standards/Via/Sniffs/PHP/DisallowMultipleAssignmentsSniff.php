<?php
/**
 * Via_Sniffs_PHP_DisallowMultipleAssignmentsSniff.
 *
 * PHP version 5
 *
 * @author    Joel Jacob <joel@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

namespace Via\Sniffs\PHP;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\DisallowMultipleAssignmentsSniff as SquizDisallowMultipleAssignmentsSniff;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Via_Sniffs_PHP_DisallowMultipleAssignmentsSniff.
 *
 * @author    Joel Jacob <joel@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class DisallowMultipleAssignmentsSniff extends SquizDisallowMultipleAssignmentsSniff {


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
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
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
