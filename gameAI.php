<?php
    
    define("humanSymbol", "X");
    define("computerSymbol", "O");
    
    class MoveAndScore
    {
        public $move;
        public $score;
    }
    
    function checkWin($board)
    {
        $win = FALSE;
        if (
                (($board[0] == $board[1]) && ($board[1] == $board[2]) && ($board[0] != "-")) ||
                (($board[3] == $board[4]) && ($board[4] == $board[5]) && ($board[3] != "-")) ||
                (($board[6] == $board[7]) && ($board[7] == $board[8]) && ($board[6] != "-")) ||
                (($board[0] == $board[3]) && ($board[3] == $board[6]) && ($board[0] != "-")) ||
                (($board[1] == $board[4]) && ($board[4] == $board[7]) && ($board[1] != "-")) ||
                (($board[2] == $board[5]) && ($board[5] == $board[8]) && ($board[2] != "-")) ||
                (($board[0] == $board[4]) && ($board[4] == $board[8]) && ($board[0] != "-")) ||
                (($board[2] == $board[4]) && ($board[4] == $board[6]) && ($board[2] != "-")))
            {
                $win = TRUE;
            }
            return $win;
    }

    function checkDraw($board)
    {
        $draw = TRUE;
        for($i=0; $i<9; $i++)
        {
            if($board[$i] == "-")
            {
                $draw = FALSE;
                break;
            }
        }
        return $draw;
    }

    function printBoard($Board)
    {
        echo "PRINTING BOARD <br> -----------------------------------------<br>";
        for($i=0;$i<9;$i++)
        {
            echo $Board[$i];
            echo "\t";
            if (($i+1)%3 == 0)
                echo "<br>";
        }
        echo "-----------------------------------------------------<br>";    
    }

    function calculateScore($nextMove, $symbol, $height)
    {
        //echo "In calculateScore <br>";
        if (checkWin($nextMove))
        {
            //printBoard($nextMove);
            if ($symbol == computerSymbol)
                return (10-$height);
            else if ($symbol == humanSymbol)
                return ($height-10);
        }
        else if (checkDraw($nextMove))
            return 0;
        else
        {
            // find all next possible moves
            
            $allPossibleNextMove;   //array to store MoveAndScore objects
            $possibleMoveCount = 0;
        
            // find all possible moves of parent
            for($i=0; $i<9; $i++)
            {
                if($nextMove[$i]=="-")
                {
                    $allPossibleNextMove[$possibleMoveCount] = new MoveAndScore();    //move varies from 1-9
                    $allPossibleNextMove[$possibleMoveCount]->move = $i+1;
                    $allPossibleNextMove[$possibleMoveCount]->score = 0;

                    $possibleMoveCount++;
                }
            }

            $tempBoard;
            //echo $possibleMoveCount;
            //echo "<br>";
            //calculate score of each of the next possible move (children)
            for($i=0; $i < $possibleMoveCount; $i++)
            {
                $tempBoard = $nextMove;
                if ($symbol == computerSymbol)
                {
                    $tempBoard[($allPossibleNextMove[$i]->move)-1] = humanSymbol; //printBoard($tempBoard);
                    $allPossibleNextMove[$i]->score = calculateScore($tempBoard, humanSymbol, $height+1);
                    
                    /*echo "Computer <br>";
                    echo $allPossibleNextMove[$i]->score;
                    echo "<br>";*/
                }
                else
                {
                    $tempBoard[($allPossibleNextMove[$i]->move)-1] = computerSymbol; //printBoard($tempBoard);
                    $allPossibleNextMove[$i]->score = calculateScore($tempBoard, computerSymbol, $height+1);
                    
                    /*echo "Human <br>";
                    echo $allPossibleNextMove[$i]->score;
                    echo "<br>";*/
                }     
            } // end of for

            //find the maximum or minimum score among children (as required)
            if ($symbol == computerSymbol)  // next move would be by human. Thus choose the lowest score
            {
                $minimum = 999999;
                $finalPosition;
                for($i=0; $i < $possibleMoveCount; $i++)
                {
                    if(($allPossibleNextMove[$i]->score) < $minimum)
                    {
                        $minimum = $allPossibleNextMove[$i]->score;
                        $finalPosition = $allPossibleNextMove[$i]->move;
                    }
                }
                return $minimum;
            }
            else
            {
                // find largest score
                $largest = -999999;
                $finalPosition;
                for($i=0; $i < $possibleMoveCount; $i++)
                {
                    if(($allPossibleNextMove[$i]->score) > $largest)
                    {
                        $largest = $allPossibleNextMove[$i]->score;
                        $finalPosition = $allPossibleNextMove[$i]->move;
                    }
                }
                return $largest;       
            }

        }        
        
    }

    function nextMove($currentBoard)
    {
        $allPossibleNextMove;   //array to store MoveAndScore objects
        $possibleMoveCount = 0;
        
        // find all possible moves
        for($i=0;$i<9;$i++)
        {
            if($currentBoard[$i]=="-")
            {
                $allPossibleNextMove[$possibleMoveCount] = new MoveAndScore();    //move varies from 1-9
                $allPossibleNextMove[$possibleMoveCount]->move = $i+1;
                $allPossibleNextMove[$possibleMoveCount]->score = 0;

                $possibleMoveCount++;
            }
        }
        
        
        // find score of each of the possible move
        for($i=0; $i < $possibleMoveCount; $i++)
        {
            //echo $allPossibleNextMove[$i]->move;    
            $nextMove = $currentBoard;
            $nextMove[($allPossibleNextMove[$i]->move)-1] = computerSymbol;
            $allPossibleNextMove[$i]->score = calculateScore($nextMove, computerSymbol, 0); //calculateScore take move position, the player symbol (i.e. the player who is making this move) and height. We are analyzing the computer's move
        }

        // find largest score
        $largest = -999999;
        $finalPosition;
        for($i=0; $i < $possibleMoveCount; $i++)
        {
            //echo $allPossibleNextMove[$i]->score;
            //echo "<br>";

            if(($allPossibleNextMove[$i]->score)>$largest)
            {
                $largest = $allPossibleNextMove[$i]->score;
                $finalPosition = $allPossibleNextMove[$i]->move;
            }
        }
        return $finalPosition;        

    }// end of function
    
    /*----------------------------------------------------------------------------------------------------------------------------*/
    $startTime = time();
    
    // get the q parameter from URL
    $input = $_REQUEST["q"];
    $computerMove="";
    
    // split up input to get current board 
    $board = explode(",", $input);
    
    //figure out next move    
    $computerMove = nextMove($board);
    echo $computerMove;
?>