<?php
/**
 * Squiz_Sniffs_Functions_MultiLineFunctionDeclarationSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      https://github.com/viastudio/coding-standards
 */

if (class_exists('PEAR_Sniffs_Functions_FunctionDeclarationSniff', true) === false) {
    $error = 'Class PEAR_Sniffs_Functions_FunctionDeclarationSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Squiz_Sniffs_Functions_MultiLineFunctionDeclarationSniff.
 *
 * Ensure single and multi-line function declarations are defined correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 1.5.2
 * @link      https://github.com/viastudio/coding-standards
 */
class Via_Sniffs_Functions_SingleLineFunctionDeclarationSniff extends PEAR_Sniffs_Functions_FunctionDeclarationSniff {
    /**
     * Processes single-line declarations.
     *
     * Just uses the Generic KR brace sniff.
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
