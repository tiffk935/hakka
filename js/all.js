$(function () {
  $('.ham').on('click', function () {
    $('.ham-content').fadeToggle();
  })
  $('.header a, .ham-content a, .btn-go-map').click(function(){
    $('.ham-content').fadeOut();
    
    const isViewingIdx = $(this).data('section') === 'index'
    if (isViewingIdx){
      $('.section-index-sections').show();
      $('.section-charts').hide();
    } else {
      $('.section-index-sections').hide();
      $('.section-charts').show();
    }
    
    $('html, body').animate({
      scrollTop: $( $(this).attr('href') ).offset().top
    }, 500);
    return false;
  });
  $(window).scroll(function () {
    if ($(this).scrollTop() > 1) $('.header').addClass('fixed')
    else $('.header').removeClass('fixed')
  });
});

$(window).on("load", function () {});

var app = new Vue({
  el: "#quiz-app",
  data: { 
    questions: [],
    quizIdx: 0,
    selectedAnswer: -1,
    answeringCorrect: undefined,
    explanations: [],
    hasAnswered: false,
    score: 0,
    hasAnswerDone: false,
  },
  computed: {
    currentBgbyQuizIdx() {
      return this.quizIdx % 2 === 0 ? 'purple-dark' : 'purple-light'
    },
    nextStep() {
      if (this.quizIdx === 4 && this.hasAnswered) return '結算成績'
      else if (!this.hasAnswered) return '確認回答'
      else return '前往下一題'
    },
  },
  created() {
    fetch("./quiz.json")
      .then((e) => e.json())
      .then((e) => { this.questions = e; });
  },
  methods: {
    selectOption(val) {
      if (this.hasAnswered) return
      this.selectedAnswer = val      
    },
    answer() {
      if (this.selectedAnswer === -1) return 
      if (this.hasAnswered) {
        this.nextQuestion()
        return
      }
      const isAnswerCorrect = this.selectedAnswer === this.questions[this.quizIdx].answer
      this.hasAnswered = true
      if (isAnswerCorrect) {
        $('.dialog-correct').addClass('active')
        this.addScore()
      } else {
        $('.dialog-wrong').addClass('active')
      }
      this.explanations = this.questions[this.quizIdx].explain
      setTimeout(() => {
        $('.dialog').removeClass('active')
      }, 1000);
    },
    addScore() {
      this.score += 20
    },
    nextQuestion(){ 
      if (this.quizIdx === 4) this.endQuiz()
      else {
        this.quizIdx += 1;
        this.resetStatus();
      }
    },
    resetStatus() {
      this.explanations = []
      this.selectedAnswer = -1
      this.hasAnswered = false
    },
    endQuiz() {
      this.hasAnswerDone = true
    }
  },
});


