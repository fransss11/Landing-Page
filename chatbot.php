<?php
include 'database.php';  
// Ambil data chatbot
$chatbot_sql = "SELECT * FROM chatbot";
$chatbot_result = $conn->query($chatbot_sql);
$chatbot_data = [];
if ($chatbot_result->num_rows > 0) {
    while($row = $chatbot_result->fetch_assoc()) {
        $chatbot_data[] = $row;
    }
}

$conn->close();
?>
<a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>
<div class="chatbot-label">Butuh Bantuan?</div>
<div class="chatbot-btn">
  <i class="fa fa-comments"></i>
</div>
<div class="chatbot-container" id="chatbot">
  <div class="chatbot-header">Lisa Mitra Mandiri Chat</div>
  <div class="chatbot-body" id="chatbot-body">
    <div class="message bot-message">
        Selamat datang! Ada yang bisa saya bantu?
    </div>
  </div>
  <div class="chatbot-footer">
    <input type="text" id="chatbot-input" placeholder="Ketik pesan..." />
    <button id="chatbot-send"><i class="fa fa-paper-plane"></i></button>
  </div>
</div>
<!-- JS Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
    
    // Chatbot data dari database
    const chatbotData = <?php echo json_encode($chatbot_data); ?>;
    
    // Chatbot functionality
    document.addEventListener('DOMContentLoaded', function() {
        const chatbotBtn = document.querySelector('.chatbot-btn');
        const chatbotContainer = document.querySelector('.chatbot-container');
        const sendBtn = document.getElementById('chatbot-send');
        const userInput = document.getElementById('chatbot-input');
        const chatbotBody = document.getElementById('chatbot-body');
        
        // Toggle chatbot visibility
        chatbotBtn.addEventListener('click', function() {
            chatbotContainer.style.display = chatbotContainer.style.display === 'flex' ? 'none' : 'flex';
        });
        
        // Send message function
        function sendMessage() {
            const message = userInput.value.trim();
            if (message === '') return;
            
            // Add user message to chat
            addMessage(message, 'user');
            userInput.value = '';
            
            // Process the response
            setTimeout(() => {
                const response = processUserMessage(message);
                addMessage(response, 'bot');
            }, 600);
        }
        
        // Send button click
        sendBtn.addEventListener('click', sendMessage);
        
        // Enter key press
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        // Add message to chat
        function addMessage(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(sender + '-message');
            messageDiv.textContent = message;
            chatbotBody.appendChild(messageDiv);
            
            // Scroll to bottom
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }
        
        // Process user message and find response
        function processUserMessage(message) {
            message = message.toLowerCase();
            
            // Default response if no match found
            let response = "Maaf, saya tidak mengerti pertanyaan Anda. Silakan coba pertanyaan lain.";
            
            // Check for matches in chatbot data
            for (let i = 0; i < chatbotData.length; i++) {
                const pertanyaan = chatbotData[i].pertanyaan_chat.toLowerCase();
                
                if (message.includes(pertanyaan) || pertanyaan.includes(message)) {
                    response = chatbotData[i].jawaban_chat;
                    break;
                }
            }
            
            return response;
        }
        
        // Close chatbot when clicking outside
        document.addEventListener('click', function(event) {
            if (chatbotContainer.style.display === 'flex') {
                if (!chatbotContainer.contains(event.target) && event.target !== chatbotBtn && !chatbotBtn.contains(event.target)) {
                    chatbotContainer.style.display = 'none';
                }
            }
        });
    });
</script>
<style>
  .chatbot-btn {
    position: fixed;
    bottom: 20px;
    right: 2px;
    z-index: 1000;
    background-color: #28a745;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    animation: green-glow 0.8s infinite alternate;
  }

  /* @keyframes green-glow {
    0% {
      box-shadow: 0 0 10px #28a745, 0 0 20px #28a745;
    }
    100% {
      box-shadow: 0 0 15px #28a745, 0 0 25px #28a745, 0 0 30px #28a745;
    }
  } */

  .chatbot-label {
    position: fixed;
    bottom: 80px;
    right: 2px;
    z-index: 1000;
    background-color: #28a745;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-weight: bold;
  }

  .chatbot-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 1000;
    width: 350px;
    height: 450px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .chatbot-header {
    background-color: #28a745;
    color: white;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    font-size: 16px;
    border-radius: 15px 15px 0 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .chatbot-body {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background-color: #fff;
    background-image: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                     url('data:image/svg+xml,%3Csvg width="20" height="20" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath d="M0 0h20v20H0z" fill="%23f9f9f9" fill-opacity="0.4"/%3E%3C/svg%3E');
  }

  .chatbot-footer {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-top: 1px solid #eaeaea;
    background-color: #f8f8f8;
    border-radius: 0 0 15px 15px;
  }

  .chatbot-footer input {
    flex: 1;
    padding: 10px 15px;
    margin-right: 10px;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 14px;
    background-color: #fff;
    transition: border-color 0.2s;
  }

  .chatbot-footer input:focus {
    outline: none;
    border-color: #28a745;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
  }

  .chatbot-footer button {
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
  }

  .chatbot-footer button:hover {
    background-color: #218838;
    transform: scale(1.05);
  }

  .chatbot-footer button i {
    font-size: 16px;
  }
  
  /* Message styles */
  .message {
    color: black;
    margin-bottom: 10px;
    padding: 10px 15px;
    border-radius: 18px;
    max-width: 80%;
    word-wrap: break-word;
  }
  
  .user-message {
    align-self: flex-end;
    background-color: #dcf8c6;
    margin-left: auto;
    border-bottom-right-radius: 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  }
  
  .bot-message {
    background-color: #f1f0f0;
    margin-right: auto;
    border-bottom-left-radius: 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  }
</style>