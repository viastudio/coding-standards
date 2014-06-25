<?php
/**
 * Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff.
 *
 * PHP version 5
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */

if (class_exists('PEAR_Sniffs_Functions_FunctionDeclarationSniff', true) === false) {
    $error = 'Class PEAR_Sniffs_Functions_FunctionDeclarationSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff.
 *
 * @author    Jason McCreary <jmac@viastudio.com>
 * @link      https://github.com/viastudio/coding-standards
 */
class Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff extends PEAR_Sniffs_Functions_FunctionDeclarationSniff {
    /**
     * Processes single-line declarations.
     *
     * Delegates to the Generic KR brace sniff.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processSingleLineDeclaration(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        if (class_exists('Generic_Sniffs_Functions_OpeningFunctionBraceKernighanRitchieSniff', true) === false) {
            throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_Functions_OpeningFunctionBraceKernighanRitchieSniff not found');
        }

        $sniff = new Generic_Sniffs_Functions_OpeningFunctionBraceKernighanRitchieSniff();

        $sniff->process($phpcsFile, $stackPtr);
    }
}
