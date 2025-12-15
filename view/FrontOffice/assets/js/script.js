document.addEventListener("DOMContentLoaded", function() {
    // Like buttons
    document.querySelectorAll(".like-btn").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.stopPropagation(); // Prevent triggering question toggle
            const id = this.dataset.id || this.closest('.question-card').dataset.id;
            likeQuestion(id, this);
        });
    });
});

// Toggle question details (expand/collapse)
function toggleQuestionDetails(questionId) {
    const questionCard = document.querySelector(`[data-id="${questionId}"]`);
    const detailsSection = document.getElementById(`details-${questionId}`);
    const expandIcon = questionCard.querySelector('.expand-icon');
    
    if (detailsSection.style.display === "none") {
        // Expand
        detailsSection.style.display = "block";
        questionCard.classList.add('expanded');
        expandIcon.style.transform = "rotate(180deg)";
        
        // Load responses
        loadResponses(questionId);
    } else {
        // Collapse
        detailsSection.style.display = "none";
        questionCard.classList.remove('expanded');
        expandIcon.style.transform = "rotate(0deg)";
    }
}

// Load responses for a question (NEW VERSION - USES QUESTION ID)
function loadResponses(questionId) {
    const container = document.getElementById(`responses-${questionId}`); // Note the template literal
    
    if (!container) {
        console.error(`Responses container not found for question ${questionId}`);
        return;
    }
    
    container.innerHTML = '<p class="loading-text">Loading responses...</p>';

    fetch(`../controller/get_response.php?id=${questionId}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                container.innerHTML = `<p class="no-responses" style="color: #e74c3c;">Error: ${data.error}</p>`;
                return;
            }
            
            if (data.length === 0) {
                container.innerHTML = '<p class="no-responses">No responses yet. Be the first to respond!</p>';
                return;
            }
            
            container.innerHTML = '';
            data.forEach(r => {
                const responseItem = document.createElement('div');
                responseItem.className = 'response-item';
                responseItem.innerHTML = `
                    <div class="response-content">${r.CONTENT}</div>
                    <div class="response-meta">
                        <span>📅 ${r.date_response}</span>
                        <span class="response-likes">❤️ ${r.likes} likes</span>
                    </div>
                `;
                container.appendChild(responseItem);
            });
        })
        .catch(err => {
            console.error("Load responses error:", err);
            container.innerHTML = '<p class="no-responses" style="color: #e74c3c;">Error loading responses.</p>';
        });
}

// Toggle reply form
function toggleReplyForm(questionId) {
    const replyForm = document.getElementById(`reply-form-${questionId}`);
    
    if (!replyForm) {
        console.error(`Reply form not found for question ${questionId}`);
        return;
    }
    
    if (replyForm.style.display === "none") {
        replyForm.style.display = "block";
    } else {
        replyForm.style.display = "none";
        // Reset form
        const textarea = replyForm.querySelector('textarea');
        if (textarea) textarea.value = '';
    }
}

// Submit response
function submitResponse(event, questionId) {
    event.preventDefault();
    
    const form = event.target;
    const content = form.querySelector('textarea[name="content"]').value.trim();
    
    if (!content) {
        alert("Please write a response");
        return;
    }

    const formData = new FormData();
    formData.append("id_question", questionId);
    formData.append("content", content);

    fetch("../controller/send_reply.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("✅ Response sent successfully!");
            form.reset();
            toggleReplyForm(questionId);
            loadResponses(questionId); // Reload responses
        } else {
            alert("❌ Error: " + (data.msg || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("❌ Failed to send response");
    });
}

// Like question
function likeQuestion(id, btn) {
    const formData = new FormData();
    formData.append("id_question", id);

    fetch("../controller/like_question.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            const likeCount = btn.querySelector(".like-count");
            if (likeCount) {
                likeCount.textContent = data.likes;
            }
        } else {
            console.error("Like failed:", data.msg);
        }
    })
    .catch(err => console.error("Like error:", err));
}

// Delete question
function deleteQuestion(id) {
    if (!confirm('⚠️ Are you sure you want to delete this question and all its responses?')) return;
    
    const formData = new FormData();
    formData.append('id_question', id);
    
    fetch('../controller/delete_question.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("✅ Question deleted successfully!");
            location.reload();
        } else {
            alert("❌ Error: " + (data.msg || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Delete error:", err);
        alert("❌ Failed to delete question");
    });
}