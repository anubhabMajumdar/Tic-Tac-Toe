<html>

<head>
    <link rel="stylesheet" href="Styles/game.css">

    <script type="text/javascript">
        var globalTurn = "X";
        var board = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
        var gameStatus = "NO RESULT";

        function controller(position) {
            var moveOK;
            /* Human Player */
            if (gameStatus == "NO RESULT") {
                moveOK = putSymbol(position);
                checkGameLogic();
            }

            /* Game AI */
            if (moveOK == true && (gameStatus == "NO RESULT")) {
                var str = board.toString();
                var computerMove;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        computerMove = xmlhttp.responseText;
                        //alert(computerMove);
                        moveOk = putSymbol(computerMove);
                        checkGameLogic();
                    }
                };

                xmlhttp.open("GET", "gameAI.php?q=" + str, true);
                xmlhttp.send();

            }
        }

        function putSymbol(input) {
            var moveOK = false;
            if (gameStatus == "NO RESULT" && board[input - 1] == "-") {
                if (globalTurn == "X") {
                    document.getElementById(input).innerHTML = "<img src='Images/cross.png' />";
                    board[input - 1] = "X";
                    globalTurn = "O";
                    moveOK = true;
                }
                else {
                    document.getElementById(input).innerHTML = "<img src='Images/zero.png' />";
                    board[input - 1] = "O";
                    globalTurn = "X";
                    moveOK = true;
                }
                //checkGameLogic();
            }
            return moveOK;
        }

        function checkGameLogic() {
            var i;
            var gameOverFlag = true;
            var win = false;
            var winSymbol;
            /* Check for win */

            if (
                ((board[0] == board[1]) && (board[1] == board[2]) && (board[0] != "-")) ||
                ((board[3] == board[4]) && (board[4] == board[5]) && (board[3] != "-")) ||
                ((board[6] == board[7]) && (board[7] == board[8]) && (board[6] != "-")) ||
                ((board[0] == board[3]) && (board[3] == board[6]) && (board[0] != "-")) ||
                ((board[1] == board[4]) && (board[4] == board[7]) && (board[1] != "-")) ||
                ((board[2] == board[5]) && (board[5] == board[8]) && (board[2] != "-")) ||
                ((board[0] == board[4]) && (board[4] == board[8]) && (board[0] != "-")) ||
                ((board[2] == board[4]) && (board[4] == board[6]) && (board[2] != "-"))
               ) {
                win = true;
                if (globalTurn == "X")
                    winSymbol = "O";
                else
                    winSymbol = "X";
                gameStatus = winSymbol + " win";
                displayGameStatus();
            }

            else {
                /* Check for game over*/
                for (i = 0; i < 9; i++) {
                    if (board[i] == "-") {
                        gameOverFlag = false;
                        break;
                    }
                }
                if (gameOverFlag == true) {
                    gameStatus = "DRAW";
                    displayGameStatus();
                }
            }
        }

        function displayGameStatus() {
            alert("Result: " + gameStatus);
        }

        function reset() {
            var i;
            for (i = 1; i <= 9; i++) {
                document.getElementById(i).innerHTML = "<img src='Images/blank.png' />";
            }

            /* Re initialize global variables */
            gameStatus = "NO RESULT";
            globalTurn = "X";
            board = ["-", "-", "-", "-", "-", "-", "-", "-", "-"];
        }

    </script>

</head>
<body>
    <div id="container">
        <div id="header">
            <h1>Unbeatable Tic Tac Toe game</h1>
        </div>

        <div id="main">

            <table id="board" style="cursor: pointer">
              <tr>
                <td id="1" onclick="controller(1)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="2" onclick="controller(2)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="3" onclick="controller(3)"><img src='Images/blank.png' alt="Blank"/></td>
              </tr>
              <tr>
                <td id="4" onclick="controller(4)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="5" onclick="controller(5)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="6" onclick="controller(6)"><img src='Images/blank.png' alt="Blank"/></td>
              </tr>
              <tr>
                <td id="7" onclick="controller(7)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="8" onclick="controller(8)"><img src='Images/blank.png' alt="Blank"/></td>
                <td id="9" onclick="controller(9)"><img src='Images/blank.png' alt="Blank"/></td>
              </tr>
            </table>

            <div style="text-align: center; padding-top: 20px;">
                <button id="resetButton" onclick="reset()">Reset</button>
            </div>

            <div>
                <h1>
                    Rules <br>-------------------------<br>
                </h1>
                <h2>
                    1. You play first because you are being offered maximum chance of winning. <br>
                    2. Just click on the cell you want to mark to begin the game. <br>
                    3. Please wait while the computer figures out its next move. <br>
                </h2>
            </div>

        </div>
        
        <div id="footer">
            &copy; Anubhab Majumdar
        </div>
     </div>
</body>
</html>