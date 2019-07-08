<?php

namespace Squirrel\Strings\Exception;

class StringException extends \Exception
{
    /**
     * @var string Original call which lead to the exception
     */
    private $originCall = '';

    /**
     * @var string File in which the problem originated
     */
    private $originFile = '';

    /**
     * @var string Line on which the problem originated
     */
    private $originLine = '';

    /**
     * @param string $originCall Original call which lead to the exception
     * @param string $originFile File in which the problem originated
     * @param string $originLine Line on which the problem originated
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $originCall,
        string $originFile,
        string $originLine,
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->originCall = $originCall;
        $this->originFile = $originFile;
        $this->originLine = $originLine;
    }

    /**
     * @return string
     */
    public function getOriginCall(): string
    {
        return $this->originCall;
    }

    /**
     * @return string
     */
    public function getOriginFile(): string
    {
        return $this->originFile;
    }

    /**
     * @return string
     */
    public function getOriginLine(): string
    {
        return $this->originLine;
    }
}
