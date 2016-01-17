<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 07/01/16
 */

class CodeGenerator {

    // Constants defining tags for CodeGenerator behavior (options)


    /**
     * value for mode when first part of code (letters part) is generated with letter for each words
     */
    const MODE_FIRST_WORD_LETTERS = 'FWL';

    /**
     * value for mode when first part of code (letters part) is generated with letters of the firsts letters of the first word
     */
    const MODE_FIRST_LETTERS = 'FL';

    /**
     * value for mode when first part of code (letters part) is generated with upper case letters
     */
    const CASE_UPPER = 'UPPER';

    /**
     * value for mode when first part of code (letters part) is generated with lower case letters
     */
    const CASE_LOWER = 'LOWER';

    /**
     * value for mode when first part of code (letters part) is generated preserving case
     */
    const CASE_PRESERVE = 'PRESERVE';

    /**
     * @var array list of words after space separation
     */
    private $wordsArray;

    /**
     * @var mixed count of words
     */
    private $wordsCount;

    /**
     * @var string the generated code
     */
    private $code;

    /**
     * @var int
     */
    private $nChar;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $incrementWith;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var string
     */
    private $case;

    /**
     * @var bool generate hexadecimal numbers at right of code if true, else decimal
     */
    private $hexNumbers;

    /**
     * @param string $input The sequence of words separated by space
     * @param int $increment number to add in the number part of the code
     * @param int $length length of code
     * @param int $nChar number of letters in left part of code. Must be < to length
     * @param bool $firstWordLettersMode If true, generate letter part code with first letter of $nChar firsts words, if false, generate the letter part with $nChar first letters of the first word
     * @param string $case UPPER: All letters are capital letters | LOWER: all letters are minus letters | PRESERVE: case sensitive
     * @param bool $hex generate hexadecimal numbers at right of code if true, else decimal
     */
    public function __construct($input, $increment = 0, $length = 8, $nChar = 4, $firstWordLettersMode = true, $case = "UPPER", $hex = false)
    {
        // check args logical use
        if ( !is_numeric($increment) || !is_numeric($length) || !is_numeric($nChar)) // when numbers are not numbers
            throw new InvalidArgumentException(__("Numeric value expected"));
        if ( !(strtoupper($case) === $this::CASE_LOWER || strtoupper($case) === $this::CASE_UPPER || strtoupper($case) === $this::CASE_PRESERVE) ) // when case option not exists
            throw new InvalidArgumentException("Case argument not implemented, get $case, possible choices: " . $this::CASE_LOWER . ", " . $this::CASE_UPPER . ", " . $this::CASE_PRESERVE . "." );
        if ( $nChar >= $length ) // when we wants a code with too much letters than length - 1 (one char is reserved for numbers)
            throw new InvalidArgumentException(__("You can't generate a code of $length characters with $nChar letters. Please, reduce number of letters (nChar) at length -1 or increase the length of code.", 'timelab'));

        if ($hex)
            $lengthOfNumberPart = ceil(log($increment+2, 16));
        else
            $lengthOfNumberPart = ceil(log($increment+2, 10));
        if ( $length - $nChar - $lengthOfNumberPart < 0 )
            throw new LogicException(__("Can't add $increment to number part of code: maximum reached, represents $lengthOfNumberPart chars.", 'timelab'));

        //load input properties
        $this->wordsCount = substr_count( $input, " ") + 1; // count words
        $this->wordsArray = explode(" ", $input); // store words in array

        // load settings
        $this->nChar = $nChar;
        $this->length = $length;
        $this->incrementWith = $increment;
        $this->case = strtoupper($case);
        $this->hexNumbers = $hex;

        if($firstWordLettersMode===true)
            $this->mode = $this::MODE_FIRST_WORD_LETTERS;
        else
            $this->mode = $this::MODE_FIRST_LETTERS;
    }

    /**
     * generate the code and return this value
     * @return string the generated code
     */
    public function generateCode(){
        if ($this->wordsCount === 0)
            return "";

        if ( $this->wordsCount < $this->nChar )
            $this->completeArray();
        elseif ($this->wordsCount > $this->nChar)
            $this->removeSupWords();

        $this->generate();

        return $this->code;
    }

    /**
     * Complete words array with a default string in aim to right complete the code
     * @param string $defaultString string added
     */
    private function completeArray($defaultString = "_")
    {
        $n = $this->nChar - $this->wordsCount;
        while ($n != 0)
        {
            $this->wordsArray[] = $defaultString;
            $n -= 1;
        }
    }

    /**
     * Remove from words array the supplement of words specified in $this->nChar
     */
    private function removeSupWords()
    {
        $tmpArray = array();
        $n = 0 ;
        while ($n < $this->nChar )
        {
            $tmpArray[] = $this->wordsArray[$n];
            $n++;
        }
        $this->wordsArray = $tmpArray;
    }

    /**
     * internal steps to process code generation taking in account modes and case options (cf. constants)
     */
    private function generate()
    {

        // add letters
        if($this->mode == $this::MODE_FIRST_LETTERS)
            foreach ($this->wordsArray as $word)
                $this->code .= $this->getLetters($word, 1);

        if($this->mode == $this::MODE_FIRST_WORD_LETTERS)
            $this->code = $this->getLetters($this->wordsArray[0], $this->nChar);

        // complete the left with number
        $this->finalizeWithNumber();

    }

    /**
     * Extract letters with case option
     * @param string $word the word to process
     * @param int $n Number of letters to extract
     * @return string The letter after case transformation
     */
    private function getLetters($word, $n)
    {
        $word = str_pad($word, $n, "A");
        if ($this->case === $this::CASE_PRESERVE)
            return substr($word, 0, $n);
        if ($this->case === $this::CASE_UPPER)
            return substr(strtoupper($word), 0, $n);
        if ($this->case === $this::CASE_LOWER)
            return substr(strtolower($word), 0, $n);
    }

    /**
     * Add a string representation of number after increment and left pad with 0
     */
    private function finalizeWithNumber()
    {
        $i = 1 + $this->incrementWith;

        if($this->hexNumbers == true )
            $strToAdd = dechex($i);
        else
            $strToAdd = $i;

        if($this->case == $this::CASE_UPPER)
            $strToAdd = strtoupper($strToAdd);

        $str = str_pad($strToAdd, $this->length - $this->nChar, "0", STR_PAD_LEFT);
        $this->code .= $str;
    }

}