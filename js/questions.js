(function(){ //flash message function
  function showFlash(message, type = 'error') {
    const flash = document.getElementById('flash-message');

    if (!flash) return;
    flash.querySelector('.flash__text').textContent = message;
    flash.style.background = type === 'error' ? '#f44336' : '#4CAF50';
    flash.classList.add('show');
    setTimeout(()=> flash.classList.remove('show'), 4000);

  }

  let currentFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
  let currentModule = new URLSearchParams(window.location.search).get('module') || '';
  let searchTerm    = '';
  let typingTimer   = null;

  const grid         = document.getElementById('questions-grid');
  const searchInput  = document.getElementById('search-input');
  const moduleSelect = document.getElementById('module-select');
  const filters      = Array.from(document.querySelectorAll('.filter-link'));

  // method to highlight search term in question title and text (useful for search results)
  function highlightMatch(text, term) {
    if (!term) return text;
    const escaped = term.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    const re = new RegExp(`(${escaped})`,'gi');
    return text.replace(re,'<span class="highlight">$1</span>');
  }



  function loadQuestions() {
    const params = new URLSearchParams({ filter: currentFilter, search: searchTerm, module: currentModule });
    fetch('app/api/get_questions.php?' + params)
      .then(r=>r.json())
      .then(renderGrid)
      .catch(err=>{
        console.error(err);
        showFlash("Network error loading questions.", "error");
      });
  }
  //entire function to render the grid of questions
  function renderGrid(data) {
    grid.innerHTML = '';
    if (!data.length) {
      grid.innerHTML = '<p class="label-1">No questions found. Please try searching using different keywords.</p>';
      return;
    }
    data.forEach(q => {
      const ht = highlightMatch(q.title, searchTerm);
      const hq = highlightMatch(q.question_text, searchTerm).replace(/\n/g,'<br>');

      const col  = document.createElement('div'); col.className='question-column';
      const card = document.createElement('div'); card.className='question-card';
      
      card.innerHTML = `
        <div class="question-card-header">
          <span class="module-badge">${q.module_name}</span>
          <span class="status-badge ${q.status}">
            ${q.status.charAt(0).toUpperCase()+q.status.slice(1)}
          </span>
        </div>
        <div class="question-card-body">
          <h2 class="question-title">${ht}</h2>
          <p class="question-text">${hq}</p>
          <small class="question-meta">
            Asked by ${q.username} on ${q.created_at}
          </small>
        </div>
        <div class="question-card-footer">
          <button class="vote-button${q.voted?' voted':''}" data-id="${q.id}">
            <ion-icon name="caret-up" aria-hidden="true"></ion-icon> ${q.votes}
          </button>
          ${ window.USER_ROLE==='staff'
             ? `<a href="answer_question.php?id=${q.id}" class="answer-button">Answer</a>`
             : `<a href="answer_question.php?id=${q.id}" class="view-button">View</a>` }
        </div>`;

      col.appendChild(card);
      grid.appendChild(col);

    });
    attachVoteHandlers();
  }

  //  function to attach event handlers for voting buttons 
  function attachVoteHandlers() {
    grid.querySelectorAll('.vote-button').forEach(btn => {
      btn.addEventListener('click', function () {
        const qid = this.dataset.id;
        this.disabled = true; //this would prevent double clicks for each user
        
        fetch('app/api/vote_question.php', {  method: 'POST',  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ id: qid }),
        })
        .then(res => {
          if (res.status === 403) {
            showFlash("You need to be logged in as a student to vote.", "error");
            throw 'not-logged-in';
          }
          if (!res.ok) throw 'network';
          return res.json();
        })
        //updating the vote count if successful
        .then(resp => {
          if (resp.success) {
            this.innerHTML = `<ion-icon name="caret-up"></ion-icon> ${resp.votes}`;
            this.classList.toggle('voted', resp.voted);
          }
        })
        .finally(() => { this.disabled = false; });
      });
    });
  }

  //  filter pills 
  filters.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      filters.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
      currentFilter = link.dataset.filter;
      loadQuestions();
    });
  });

  //  module select 
  if (moduleSelect){
    moduleSelect.addEventListener('change', function(){
      currentModule = this.value;
      loadQuestions();
    });
  }

  // live search 
  searchInput.addEventListener('keyup', () => {
    clearTimeout(typingTimer);
    searchTerm = searchInput.value.trim();
    typingTimer = setTimeout(loadQuestions, 300);
  });

  loadQuestions();

  //close button when flash message is shown 
  document.getElementById('flash-message')
    .querySelector('.flash__close')
    .addEventListener('click', function(){
      this.closest('#flash-message').classList.remove('show');
    });


})();  
