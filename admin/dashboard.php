<?php
session_start();
include_once '../auth/db.php';

if (!isset($_SESSION['user_id'])) {            //check if user logged in
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT balance FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$balance = $row['balance'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <title>Color Game - Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="app-container">
    
        <!-- Wallet Display -->
        <div class="wallet-box">
            <div class="wallet-balance">₹<span id='wallet-balance'><?php echo number_format($balance, 2); ?></span></div>
            <div class="wallet-label"><span class="wallet-icon">💰</span> Wallet Balance</div>
            <div class="wallet-actions">
                <button class="withdraw-btn" onclick="location.href='../wallet/withdraw.php'">Withdraw</button>
                <button class="deposit-btn" onclick="location.href='../wallet/deposit.php'">Deposit</button>
            </div>
        </div>

        <div class="ticket-card">
            <div class="left-section">
              <div id="how-to-play">💬 How to play</div>
            </div>
            <div class="right-section">
                <div class="time-label">Time remaining</div>
                <div class="time" id='round-timer'>00:04</div>
                <div class="uid">20250807100050631</div>
            </div>
        </div>
    
        <!-- Bet Modal -->
        <div id="betModal" class="bet-modal" style="background-color:#5a2b2b" >
            <div class=bet-on-para>Place Bet on <span id="bet-type">Red</span></div>
            <div style="margin-top: 10px;">
                <label>Bet Amount:</label><br>
                <button class="bet-amount-btn" onclick="setBetAmount(1)">1</button>
                <button class="bet-amount-btn" onclick="setBetAmount(10)">10</button>
                <button class="bet-amount-btn" onclick="setBetAmount(100)">100</button>
                <button class="bet-amount-btn" onclick="setBetAmount(1000)">1000</button>
            </div>
    
            <div style="margin-top: 10px;">
                <label>Quantity:</label><br>
                <button class="quantity-btn" onclick="changeQty(-1)">-</button>
                <input type="number" id="bet-qty" value="1" min="1" style="width:50px;text-align:center">
                <button class="quantity-btn" onclick="changeQty(1)">+</button>
            </div>
    
            <div class="total-amount-bar" id="totalDisplay">Total amount ₹<span id=ttlAmt>1.00</span></div>
            <div class=bet-place style="text-align:right">
                <button class="btn cancle-btn" onclick="closeModal()">Cancel</button>
                <button id='placeBetBtn' class="btn place-bet-btn" onclick='placeBet(selectBetType())'>Place Bet</button>
            </div>
        </div>

        <!-- Betting Section -->
        <div class="bet-section">
            <div class="color-buttons">
                <button class="color-red" onclick="showModal('Red')">Red</button>
                <button class="color-green" onclick="showModal('Green')">Green</button>
                <button class="color-violet" onclick="showModal('Violet')">Violet</button>
            </div>
            <div class="action-buttons">
                <button onclick="showModal('Even')">Even</button>
                <button onclick="showModal('Odd')">Odd</button>
            </div>
            <div class="bet-buttons">
                    <button class=bet-btn0 onclick="showModal('0')">0</button>
                    <button class=bet-btn-odd onclick="showModal('1')">1</button>
                    <button class=bet-btn-even onclick="showModal('2')">2</button>
                    <button class=bet-btn-odd onclick="showModal('3')">3</button>
                    <button class=bet-btn-even onclick="showModal('4')">4</button>
                    <button class=bet-btn0 onclick="showModal('5')">5</button>
                    <button class=bet-btn-even onclick="showModal('6')">6</button>
                    <button class=bet-btn-odd onclick="showModal('7')">7</button>
                    <button class=bet-btn-even onclick="showModal('8')">8</button>
                    <button class=bet-btn-odd onclick="showModal('9')">9</button>
            </div>
        </div>
        <!-- How To Play Para -->
         <div role="dialog" id="bet-rule-container" style="z-index: 2004;">
            <div id="bet-rule">
                <div class="bet-rule-head">
                    <span>· How To Play ·</span>
                </div>
                <div class="bet-rule-body"  style="overflow-y: auto">
                    <div>
                        <p>30seconds 1 issue, 15 seconds to order, 5 seconds waiting for the draw.. It opens all day. The total number of trade is 2880 issues.</p>
                        <p><br></p>
                        <p>If you spent 100 to trade, after deducting 2 service fee, your contract amount is 98:</p>
                        <p><br></p>
                        <p>1. Select green: if the result shows 1,3,7,9 you will get(98*2)196; if the result shows 5, you will get(98*1.5)147</p>
                        <p><br></p>
                        <p>2. Select green: if the result shows 2,4,6,8 you will get(98*2)196; if the result shows 0, you will get(98*1.5)147</p>
                        <p><br></p>
                        <p>3. Select violet: If the result shows 0 or 5, you will get(98*3)294</p>
                        <p><br></p>
                        <p>4. Select number: if the result is same as the number you selected, you will get (98*4.5)441</p>
                        <p><br></p>
                        <p>5. Select odd: if the result shows 1,3,5,7,9 you will get (98*1.5)147</p>
                        <p><br></p>
                        <p>6. Select even: if the result shows 0,2,4,6,8 you will get (98*1.5)147</p>
                    </div>
                </div>
                <button id="bet-rule-foot">Close</button>
            </div>
        </div>
        <div class="history">
            <div class="game-history">Game History</div>
           
            <table class="history-table" id="historyTable">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Number</th>
                    <th>Even Odd</th>
                    <th>Color</th>
                </tr>
            </thead>
            <tbody>
    <!-- Rows will be injected here dynamically -->
  </tbody>
</table>

            </div>
        </div>
    </div>


                                         <!-- JavaScript Code -->
<script>
    function showModal(x) {
    document.getElementById('betModal').style.display = 'flex';
    document.getElementById('bet-type').innerText = x;
}

// Close Bet Card
function closeModal() {
    document.getElementById('betModal').style.display = 'none';
}

function selectBetType(){
    const betType = document.getElementById('bet-type');
    return betType.innerText;
}

// Bet Section
let currentBetType = '';
let betAmount = 1;
let quantity = 1;

function placeBet(type) {
    currentBetType = type;
    document.getElementById('bet-type').innerText = "Select " + type.charAt(0).toUpperCase() + type.slice(1);
    document.getElementById('betModal').style.display = 'none';
    updateTotal();
    confirmBet();
}

// Set Bet Amount
function setBetAmount(val) {
    betAmount = val;
    quantity = parseInt(document.getElementById('bet-qty').value) || 1;
    const total = betAmount * quantity;
    document.getElementById('totalDisplay').innerText = "Total amount ₹" + total.toFixed(2);
}

// Change Quantity(+/-)
function changeQty(delta) {
    const input = document.getElementById('bet-qty');
    let value = parseInt(input.value) || 1;
    value += delta;
    if (value < 1) value = 1;
    input.value = value;
    quantity = value;
    updateTotal();
}

// Total Amount Show
function updateTotal() {
    quantity = parseInt(document.getElementById('bet-qty').value) || 1;
    totalAmount = betAmount * quantity;
    document.getElementById('totalDisplay').innerText = "Total amount ₹" + totalAmount.toFixed(2);
}

// Place Bet Button
async function confirmBet() {
    const total = betAmount * quantity;

    if (!currentBetType || total <= 0) {
        alert("Please select a bet type and amount.");
        return;
    }

    try {
        const res = await fetch('../api/place_bet.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                bet_type: currentBetType,
                amount: total
            })
        });

        const json = await res.json();

        if (json.status === 'success') {
            walletBalance = json.new_balance;
            updateWalletUI(walletBalance);
            fetchRounds();
            alert("Bet placed successfully!");
        } else {
            alert(`Error: ${json.message}`);
        }
    } catch (err) {
        console.error("Bet error:", err);
        alert("Failed to place bet.");
    }
    closeModal();
}

// Update Wallet UI
function updateWalletUI(balance) {
    const walletElement = document.getElementById('wallet-balance');
    if (walletElement) {
        walletElement.innerText = `${parseFloat(balance).toFixed(2)}`;
    }
}

// Fetch Wallet from server
async function fetchWallet() {
    try {
        const res = await fetch('../api/get_wallet_balance.php');
        const json = await res.json();
        if (json.success) {
            walletBalance = json.balance;
            updateWalletUI(walletBalance);
        }
    } catch (err) {
        console.error("Error fetching wallet:", err);
    }
}

// How To Play Button active
const betRulesActive = document.getElementById('how-to-play');
betRulesActive.addEventListener("click", function () {
    document.getElementById('bet-rule-container').style.display = "block";
    document.getElementById('bet-rule').style.display = "flex";
});

// How To Play Button close
const betRulesClose = document.getElementById('bet-rule-foot');
betRulesClose.addEventListener("click", function () {
    document.getElementById('bet-rule-container').style.display = "none";
    document.getElementById('bet-rule').style.display = "none";
});

async function fetchRounds() {
    const res = await fetch('/color_game/api/get_rounds.php?limit=10');
    const json = await res.json();
    if (json.success) {
        // update table UI with json.rounds
    }
}

function fetchRoundHistory() {
    fetch("http://localhost/color_game/api/get_rounds.php?limit=10")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const historyTableBody = document.querySelector("#historyTable tbody");
                historyTableBody.innerHTML = ""; // Clear existing rows

                data.rounds.forEach(round => {
                    const row = document.createElement("tr");

                    // Period
                    const periodCell = document.createElement("td");
                    periodCell.textContent = round.period;
                    row.appendChild(periodCell);

                    // Number
                    const numberCell = document.createElement("td");
                    numberCell.textContent = round.number;
                    row.appendChild(numberCell);

                    // Even / Odd
                    const oddEvenCell = document.createElement("td");
                    oddEvenCell.textContent = round.odd_even;
                    row.appendChild(oddEvenCell);

                    // Color (dots logic)
                    const colorCell = document.createElement("td");
                    if (round.color.toLowerCase() === "violet+red") {
                        colorCell.innerHTML = `
                            <span style="display:inline-block;width:12px;height:12px;background-color:violet;border-radius:50%;"></span>
                            <span style="display:inline-block;width:12px;height:12px;background-color:red;border-radius:50%;margin-right:3px;"></span>
                        `;
                    } else {
                        colorCell.innerHTML = `
                            <span style="display:inline-block;width:12px;height:12px;background-color:${round.color.toLowerCase()};border-radius:50%;"></span>
                        `;
                    }
                    row.appendChild(colorCell);

                    // Append row
                    historyTableBody.appendChild(row);
                });
            } else {
                console.error("Failed to load history:", data.message);
            }
        })
        .catch(err => console.error("Error fetching history:", err));
}

// Fetch immediately on page load
fetchRoundHistory();
fetchWallet();

// Refresh every 5 seconds
setInterval(fetchRoundHistory, 5000);
setInterval(fetchRounds, 5000);
setInterval(fetchWallet, 5000);

fetchRounds();

async function startCountdown() {
    async function updateTimer() {
        try {
            const res = await fetch("http://localhost/color_game/api/get_current_round.php");
            const data = await res.json();

            if (data.success) {
                let timeLeft = data.time_left;
                document.getElementById('round-timer').textContent = timeLeft;

                const interval = setInterval(() => {
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        updateTimer(); // re-fetch from server for next round
                    }
                    document.getElementById('round-timer').textContent = timeLeft;
                }, 1000);
            } else {
                console.error("Round fetch failed:", data.message);
            }
        } catch (err) {
            console.error("Error fetching timer:", err);
        }
    }

    updateTimer();
}

startCountdown();




</script>

</body>
</html>
