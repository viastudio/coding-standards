<?php
/**
 * Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff.
 *
 * PHP version 5
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

namespace Via\Sniffs\Functions;

use PHP_CodeSniffer\Standards\Generic\Sniffs\Functions\OpeningFunctionBraceKernighanRitchieSniff;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Functions\FunctionDeclarationSniff;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff.
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class SingleLineFunctionDeclarationSniff extends FunctionDeclarationSniff {
    /**
     * Processes single-line declarations.
     *
     * Delegates to the Generic KR brace sniff.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processSingleLineDeclaration($phpcsFile, $stackPtr, $tokens)
    {
        $sniff = new OpeningFunctionBraceKernighanRitchieSniff();

        $sniff->process($phpcsFile, $stackPtr);
    }
}
