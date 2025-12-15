// Initialize vote data for initiatives
const voteData = [
  { yes: 187, no: 23, userVote: null },
  { yes: 142, no: 8, userVote: null },
  { yes: 165, no: 12, userVote: null }
];

// Initialize like data for posts
const postLikes = [
  { count: 47, liked: false },
  { count: 32, liked: false },
  { count: 56, liked: false }
];

// Update progress bar and percentage display
function updateVoteProgress(initiativeIndex) {
  const data = voteData[initiativeIndex];
  const total = data.yes + data.no;
  const percentage = total > 0 ? Math.round((data.yes / total) * 100) : 0;
  
  const progressFill = document.querySelector(`.progress-fill[data-initiative="${initiativeIndex}"]`);
  const percentageText = document.querySelector(`.voting-percentage[data-initiative="${initiativeIndex}"]`);
  const yesCount = document.querySelector(`.yes-count[data-initiative="${initiativeIndex}"]`);
  const noCount = document.querySelector(`.no-count[data-initiative="${initiativeIndex}"]`);
  
  if (progressFill) {
    progressFill.style.width = percentage + '%';
  }
  
  if (percentageText) {
    percentageText.textContent = percentage + '% support';
  }
  
  if (yesCount) {
    yesCount.textContent = data.yes;
  }
  
  if (noCount) {
    noCount.textContent = data.no;
  }
}

// Handle voting on initiatives
document.querySelectorAll('.vote-btn').forEach(button => {
  button.addEventListener('click', function() {
    const initiativeIndex = parseInt(this.dataset.initiative);
    const voteType = this.dataset.vote;
    const card = this.closest('.initiative-card');
    const yesBtn = card.querySelector('.vote-yes');
    const noBtn = card.querySelector('.vote-no');
    
    const currentVote = voteData[initiativeIndex].userVote;
    
    // Remove previous vote if exists
    if (currentVote) {
      voteData[initiativeIndex][currentVote]--;
    }
    
    // If clicking the same button, remove the vote
    if (currentVote === voteType) {
      voteData[initiativeIndex].userVote = null;
      yesBtn.classList.remove('active');
      noBtn.classList.remove('active');
    } else {
      // Add new vote
      voteData[initiativeIndex][voteType]++;
      voteData[initiativeIndex].userVote = voteType;
      
      // Update button states
      yesBtn.classList.toggle('active', voteType === 'yes');
      noBtn.classList.toggle('active', voteType === 'no');
    }
    
    // Update the display
    updateVoteProgress(initiativeIndex);
  });
});

// Handle post likes
document.querySelectorAll('.like-btn').forEach(button => {
  button.addEventListener('click', function() {
    const postIndex = parseInt(this.dataset.post);
    const likeCount = this.querySelector('.like-count');
    
    if (postLikes[postIndex].liked) {
      postLikes[postIndex].count--;
      postLikes[postIndex].liked = false;
      this.classList.remove('liked');
    } else {
      postLikes[postIndex].count++;
      postLikes[postIndex].liked = true;
      this.classList.add('liked');
    }
    
    likeCount.textContent = postLikes[postIndex].count;
  });
});

// Handle comment toggling
document.querySelectorAll('.comment-toggle').forEach(button => {
  button.addEventListener('click', function() {
    const postIndex = this.dataset.post;
    const commentsSection = document.querySelector(`.comments-section[data-post="${postIndex}"]`);
    
    if (commentsSection.style.display === 'none' || !commentsSection.style.display) {
      commentsSection.style.display = 'block';
      this.classList.add('active');
    } else {
      commentsSection.style.display = 'none';
      this.classList.remove('active');
    }
  });
});

// Handle comment input and send button
document.querySelectorAll('.comment-input').forEach(input => {
  const postIndex = input.dataset.post;
  const sendBtn = document.querySelector(`.send-btn[data-post="${postIndex}"]`);
  
  input.addEventListener('input', function() {
    sendBtn.disabled = !this.value.trim();
  });
  
  input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && this.value.trim()) {
      addComment(postIndex, this.value);
      this.value = '';
      sendBtn.disabled = true;
    }
  });
});

document.querySelectorAll('.send-btn').forEach(button => {
  button.addEventListener('click', function() {
    const postIndex = this.dataset.post;
    const input = document.querySelector(`.comment-input[data-post="${postIndex}"]`);
    
    if (input.value.trim()) {
      addComment(postIndex, input.value);
      input.value = '';
      this.disabled = true;
    }
  });
});

// Add comment to post
function addComment(postIndex, commentText) {
  const commentsSection = document.querySelector(`.comments-section[data-post="${postIndex}"]`);
  const existingComments = commentsSection.querySelector('.existing-comments');
  
  const commentHTML = `
    <div class="comment">
      <img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=100" alt="You" class="comment-avatar">
      <div class="comment-content-wrapper">
        <div class="comment-bubble">
          <div class="comment-author">You</div>
          <p class="comment-text">${commentText}</p>
        </div>
        <div class="comment-time">Just now</div>
      </div>
    </div>
  `;
  
  existingComments.insertAdjacentHTML('beforeend', commentHTML);
  
  // Update comment count
  const commentToggle = document.querySelector(`.comment-toggle[data-post="${postIndex}"]`);
  const commentCount = commentToggle.querySelector('.comment-count');
  commentCount.textContent = parseInt(commentCount.textContent) + 1;
}

// Handle FAQ accordion
document.querySelectorAll('.faq-question').forEach(button => {
  button.addEventListener('click', function() {
    const faqItem = this.closest('.faq-item');
    const isActive = faqItem.classList.contains('active');
    
    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
      item.classList.remove('active');
    });
    
    // Open clicked item if it wasn't active
    if (!isActive) {
      faqItem.classList.add('active');
    }
  });
});

// Handle create post form
const createPostTrigger = document.getElementById('createPostTrigger');
const createPostForm = document.getElementById('createPostForm');
const postContent = document.getElementById('postContent');
const cancelPost = document.getElementById('cancelPost');
const submitPost = document.getElementById('submitPost');

if (createPostTrigger && createPostForm) {
  createPostTrigger.addEventListener('click', function() {
    createPostTrigger.style.display = 'none';
    createPostForm.style.display = 'flex';
    postContent.focus();
  });
}

if (cancelPost) {
  cancelPost.addEventListener('click', function() {
    createPostForm.style.display = 'none';
    createPostTrigger.style.display = 'block';
    postContent.value = '';
    submitPost.disabled = true;
  });
}

if (postContent) {
  postContent.addEventListener('input', function() {
    submitPost.disabled = !this.value.trim();
  });
}

if (submitPost) {
  submitPost.addEventListener('click', function() {
    if (postContent.value.trim()) {
      alert('Post shared with the community!');
      createPostForm.style.display = 'none';
      createPostTrigger.style.display = 'block';
      postContent.value = '';
      submitPost.disabled = true;
    }
  });
}
