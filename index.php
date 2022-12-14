<?php
$ogImg = 'meta.jpg';
if(!empty($_GET['score']) || $_GET['score'] === '0'){
  if($_GET['score'] == '100') $ogImg = 'meta100.jpg';
  else if($_GET['score'] == '80') $ogImg = 'meta80.jpg';
  else if($_GET['score'] == '60') $ogImg = 'meta60.jpg';
  else if($_GET['score'] == '40') $ogImg = 'meta40.jpg';
  else if($_GET['score'] == '20') $ogImg = 'meta20.jpg';
  else if($_GET['score'] == '0') $ogImg = 'meta0.jpg';
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="客家委員會公布「全國客家人口暨語言基礎資料調查研究報告」，以近1年的時間訪問63,111位民眾，並依據《客家基本法》的客家人定義——「具有客家血緣或客家淵源，且自我認同為客家人」推估，全台灣約有466.9萬的客家人，佔全國2356.12萬人口的19.82%。">
  <meta property="og:image" content="<?= $ogImg ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <script src="./js/jquery-3.6.0.min.js"> </script>
  <script src="./js/bootstrap.bundle.js"> </script>
  <title>客家人口數據大解密</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css?1223">
  <link rel="stylesheet" href="./css/index.css?1223">
  <style>
    .canvas-wrapper60 {
      position: relative; 
      height: 0;
      padding-top: 60%; 
    }

    .canvas-wrapper75 {
      position: relative; 
      height: 0;
      padding-top: 75%; 
    }

    canvas {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
    }
  </style>
</head>
<body>
  <!-- Header Start-->
  <nav class="header">
    <div class="header-d d-none d-lg-block">
      <div class="container">
        <div class="d-flex justify-between w-100 align-items-center"><a class="logo" href="./"><img src="img/logo-s.png" alt=""></a>
          <ul class="headerList mr-auto">
            <li> <a href="#quiz-app" data-section="index">問答挑戰</a></li>
            <li> <a href="#map" data-section="charts">互動地圖</a></li>
            <li> <a href="#anchor-number" data-section="charts">數字看客家</a></li>
            <li> <a href="#anchor-news" data-section="charts">新聞最速報</a></li>
          </ul>
          <ul class="social-links">
            <li><div class="header-share" onclick="window.open('https:\/\/www.facebook.com\/sharer\/sharer.php?u=' + window.location, '', 'width = 500, height = 500')"><img src="img/arr-share.png" alt=""></div></li>
            <li><a class="hakka-news-link" href="https://hakkanews.tw/" target="_blank"><img src="img/logo.png" alt="客新聞"></a></li>
            <li><a class="hpcf-link" href="https://www.hpcf.tw/" target="_blank"><img src="img/logo2.png" alt="客家公共傳播基金會"></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="header-m d-lg-none">
      <div class="ham">
        <div class="ham-nav ham-top"></div>
        <div class="ham-nav ham-mid"></div>
        <div class="ham-nav ham-bot"></div>
      </div>
    </div>
  </nav>
  <div class="ham-content d-lg-none">
    <div class="d-flex justify-content-between"><img class="logo" src="img/logo-s.png" alt="">
      <div class="ham">
        <div class="ham-nav ham-top"></div>
        <div class="ham-nav ham-bot"></div>
      </div>
    </div>
    <ul class="hamList">
      <li> <a href="#quiz-app" data-section="index">問答挑戰</a></li>
      <li> <a href="#map" data-section="charts">互動地圖</a></li>
      <li> <a href="#anchor-number" data-section="charts">數字看客家</a></li>
      <li> <a href="#anchor-news" data-section="charts">新聞最速報</a></li>
    </ul>
  </div>
  <!-- Header End-->

  <div class="main">
    <!-- Section KV-->
    <section class="section-kv section-index-sections"><img class="d-none d-lg-block w-100" src="img/kv/kv-d.png" alt=""/></section>
    
    <!-- Section Quiz-->
    <section class="section-quiz section-index-sections" id="quiz-app">
      <div class="quiz-main" v-cloak="v-cloak">
        <div class="square" v-if="questions">
          <div class="quiz-wrap" v-if="!hasAnswerDone"><img class="quiz-title" src="img/quiz/quiz-title.png" alt="客家人口快問快答"/>
            <div class="quiz-question"><img class="question-img" :src="`img/quiz/q${quizIdx+1}.png`" alt="1"/>
              <p class="quiz-txt">{{questions[quizIdx]?.text}}</p>
            </div>
            <ul class="quiz-options">
              <li class="quiz-option" @click="selectOption(0)" :class="{'selected-option': selectedAnswer === 0}">
                <p class="location" v-if="questions[quizIdx] &amp;&amp; questions[quizIdx].location">{{questions[quizIdx]?.location[0]}}</p>
                <p>{{questions[quizIdx]?.options[0]}} </p>
              </li>
              <li class="quiz-option" @click="selectOption(1)" :class="{'selected-option': selectedAnswer === 1}">
                <p class="location" v-if="questions[quizIdx] &amp;&amp; questions[quizIdx].location">{{questions[quizIdx]?.location[1]}}</p>
                <p>{{questions[quizIdx]?.options[1]}}</p>
              </li>
              <li class="quiz-option" @click="selectOption(2)" :class="{'selected-option': selectedAnswer === 2}">
                <p class="location" v-if="questions[quizIdx] &amp;&amp; questions[quizIdx].location">{{questions[quizIdx]?.location[2]}}</p>
                <p>{{questions[quizIdx]?.options[2]}}</p>
              </li>
              <li class="quiz-option" @click="selectOption(3)" :class="{'selected-option': selectedAnswer === 3}">
                <p class="location" v-if="questions[quizIdx] &amp;&amp; questions[quizIdx].location">{{questions[quizIdx]?.location[3]}}</p>
                <p>{{questions[quizIdx]?.options[3]}}</p>
              </li>
            </ul>
            <div class="confirm-block">
              <div class="dialog-wrap"><img class="dialog dialog-correct" src="img/quiz/dialog-correct.png" alt=""/><img class="dialog dialog-wrong" src="img/quiz/dialog-wrong.png" alt=""/>
                <div class="btn-confirm" @click="answer" :class="{ 'disabled': selectedAnswer === -1 }"><span>{{ nextStep }}</span></div>
              </div>
              <div class="answer-block"><span class="answer-txt" v-for="(des,idx) in explanations">{{des}}</span>
                <div class="answer-line d-none d-lg-block"></div>
              </div>
            </div>
          </div>
          <div class="result-wrap" v-else="v-else"><img class="result-title" src="img/quiz/result-title.png" alt="問答總結"/>
            <div class="txt-wrap">
              <p class="result-intro">客家人口快問快答你獲得幾分呢？是否有許多答案與我們想的不一樣？看完解答後可以與朋友分享自己得分，並且可以繼續往下，透過《客新聞》製作的地圖來瞭解你所不知道的台灣客家人口數據現況！</p>
              <div class="score-block"><img src="img/quiz/result-img.png" alt=""/>
                <div class="score"><span class="num">{{score}}</span><span class="unit">分</span></div>
              </div>
            </div>
            <div class="result-txt"><img :src="`img/quiz/${score}.png`" alt=""/></div><div class="share-result" onclick="window.open('https:\/\/www.facebook.com\/sharer\/sharer.php?u=' + window.location, '', 'width = 500, height = 500')" style="cursor: pointer;"> <img src="img/quiz/share.png" alt=""/></div>
          </div>
        </div>
        <div class="result-redirect" v-show="hasAnswerDone"><img src="img/quiz/result-arr.png" alt=""/><a class="btn-go-map" href="#map" data-section="charts">客家人口互動地圖</a></div>
      </div>
    </section>

    <!-- Section Charts -->
    <div class="section-maps" id="map_chart">
      <section id="map">
        <div class="tk-container">
          <div class="big-title">
            <h2>
              <img src="img/map-title.svg" alt="台灣客家人口數據地圖">
            </h2>
            <div class="info">《客新聞》依據客委會調查報告，分別將全台各鄉鎮市區推估的設籍客家人 口數據依照數值高低上色，繪製成數位地圖，瀏覽地圖時可透過一旁按鈕切 換「人口數」與「人口比例」，並可點選行政區域瞭解該區的客家人口小檔 案。</div>
          </div>

          <div class="flex">
            <div>
              <div class="tabs">
                <div :class="{tab: true, active: mapTab == 'map1'}" @click="updateMap('map1')" style="background: #80A855;">依人口數</div>
                <div :class="{tab: true, active: mapTab == 'map2'}" @click="updateMap('map2')" style="background: #B17B9F;">依人口比例</div>
              </div>
      
              <div class="note">
                <div class="title">備註</div>
                <div>本調查將澎湖縣、金門縣及連江縣以「縣」為單位。</div>
              </div>
            </div>
    
            <div class="svg-wrapper">
              <svg id="svg" width="345" height="530" viewbox="0 0 345 530">
                <g id="islands" transform="translate(-652.9, -27)" style="display: none;">
                  <g id="Matsu_Islands">
                    <rect x="664.2" y="38.4" fill="#f0ce77" stroke="#231815" stroke-width="0.5" stroke-miterlimit="10" width="57.9" height="54"/>
                    <g>
                      <path fill="#231815" d="M675.2,82.3l0.7,1l0.6-0.6l0.5-0.3l-0.8-0.4L675.2,82.3z M677.1,66.5l-1.2,0.7
                        l-0.3-0.1l-0.6-0.2l-0.1-0.2l0-0.4l-0.3,0l-0.3,0.6l-0.1,0.8l0.2,0.7l0.7,0.5l0.5-0.1l1.5-0.8l0.8-0.9l0-0.5L677.1,66.5z
                        M717.9,49l-0.2-0.4l-0.7-0.4l-0.6,0.5l-0.3,0.2l0.1,0.4l-0.3,0.5l0.3,0.2l0.2,0.1l0.3,0.1l0.1,0.1l0.3-0.6l0.2-0.3l0.2-0.1
                        l0.2,0.1l0.2,0l0.3-0.1L717.9,49z M681.6,62.1l-0.2-0.2l-0.2-0.1l-0.4-0.6l-0.7-0.1l-0.6,0.4l-0.2,0.3l-0.7,0.2l-0.3,0.2
                        l-0.1,0.5l-0.1,1.2l0.1,0.4l0.3,0.2l0.3-1.4l0.4-0.5l0.4-0.1l0.3,0.1l0.7-0.4l0.3,0.2l0.4,0.6l0.4,0.3l0-0.2l-0.1-0.3l0.1-0.3
                        L681.6,62.1z M679.4,83.2l-0.7-0.2l-0.7,1.6l0.7-0.3l0.5-0.2l0.6-0.6l0-0.6l-0.3,0.1L679.4,83.2z"/>
                    </g>
                  </g>
                  <g id="Kinmen">
                    <rect x="664.2" y="100" fill="#f0ce77" stroke="#231815" stroke-width="0.5" stroke-miterlimit="10" width="57.9" height="54"/>
                    <g>
                      <path fill="#231815" d="M679.8,130.9l-1.1,0l-1.1,0.3l-0.7,0.7l-2.7,5.3l1.6,0.6l2.8-0.8l0.6-0.4l0.3-0.6l0.6-0.6
                        l0.9-0.1l0.7-1.1l0-1.7l-0.9-1L679.8,130.9z M690.6,117l0.9-0.7l0.4-1.2l-0.2-1.2l-1.4-0.9l-2.4,0.3l-0.8,0.3l-0.5,0.6l0.5,0.6
                        L690.6,117z M707.8,122.8l-3.8-3.4l-4,0.8l-1.2,2.9l-0.6,4.6l-3.7,0.5l-5.3-3.5l-4,2l-0.8,0.5l0.1,1.1l0.9,2.2l0.4,2.3l0.1,1.3
                        l-0.1,1.1l-0.4,0.7l-1.2,1.5l-0.2,0.4l0.6,1.1l1.3,0.6l1.4,0.1l2.1,1l1.8-0.9l2.1-3.2l3.8-2.4l4.3-0.4l2.7,0.9l3.6,2.3l2-2.5
                        l0.6-4.6L707.8,122.8z M681.2,122.7l-2,2.7l0.3,1.7l2.6-0.4l1.8-2.3l-0.6-2.4L681.2,122.7z"/>
                    </g>
                  </g>
                  <g id="Penghu">
                    <rect x="664.2" y="163.6" fill="#f0ce77" stroke="#231815" stroke-width="0.5" stroke-miterlimit="10" width="57.9" height="99.8"/>
                    <g>
                      <path fill="#231815" d="M670.7,236.3l-0.2,0.1l0.1,0.2l0,0l0.1,0.1l0.2-0.1l0-0.3L670.7,236.3z M670.7,236.2l0.2,0
                        l0-0.5l-0.4-0.3L670.7,236.2z M707.2,172.5l0.6,1.4l0,0l0.1,0.2l0.2-0.5l1-1.3l1.2-0.7l0.2-1l-1.3-0.8l0-0.2l-0.2-0.4l-0.3,0.5
                        L708,171l-0.9,0.7l0.2,0.5L707.2,172.5z M684.4,251l-2,0.7l-0.5,0.7l0.8,2.5l0.7,0.8l0.9-0.5l0.9-1.2l0.6-0.6l0.1-0.9l-0.1-1.1
                        L684.4,251z M692,198.3l1.6-0.3l0.2-0.7l-0.4-1l0.1-1.2l1.5-4.5l0.9-1.9l1.3-0.8l-2.2-1.9l-1.7,1.6l-1.7,5.5l0,2.5l-0.3,1.1
                        l-1.2,0.4l-1.3,0.1l-1,0.3l-0.6,0.7l-0.3,1.1h2l0.6-0.5l0.7-0.3l0.8-0.1L692,198.3z M704.4,187.5l0.5,1.8l0.6,0.8l1.2-0.3
                        l0.8-1.2l-0.5-1.9l1.3-1.3l-0.7-1.6l-1.7-1.5l-1.7-0.9l-3,2.2l2.3,2.3L704.4,187.5z M668.8,235.4l-0.3,0.1l0.1,0.2l0.1,0.1
                        l0.2-0.1L668.8,235.4L668.8,235.4z M669.7,222.7l-0.7-0.3l-0.2,0l-0.4-0.1l-0.1,0.2l-0.1,0l-0.5,1.2l0.4,0.3v0.1l0.2,0.3l1.1-0.3
                        l0.3-0.5l-0.2-0.6L669.7,222.7z M683.7,248.8l-1-1.6l-1.7-0.3l-0.6,0.6l0.1,1.3l1.1,1.3l1.2,0.4L683.7,248.8z M694.4,244l1-0.4
                        l-0.3-0.5L694.4,244z M716,244.4l-0.4,0.4l-0.2,0l0.2,0.1l-0.1,0.1l0.3,0l0.3,0.1v-0.3h-0.1L716,244.4z M717.2,200.3l1,1.1
                        l0.3-1.6l-0.7-2.1l-2.5-4.6l-0.9,1.4l-1.2,0.1l-1.6-0.2l-2.1,0.7l0.3-0.7l0.7-2.3l-2.5,0.8l-3.9,2.5L703,195h-1l-0.1,2.1l0.1,2.1
                        l0.6-0.3l0.1-0.1v-0.2l0.3-0.5l0.7,1.2l0.8,0.9l1,0.6l1.3,0.5l-1.9,1.5l-2.3,0.6l-2.1-0.5l-1.4-1.7l-0.7,1.3l2.6,2.6l2.6,1
                        l2.6-0.4l2.5-1.4l0.4-0.6l0.6-2.2l0.4-0.8l0.7-0.4l1.5-0.2l2.1-1.2l1.5,0.3L717.2,200.3z M717.1,244.8l-0.2-0.1l0,0.1h0l-0.1,1
                        l0.3,0.7l0.3,0.8v0l0,0l0.4-1l0.6-0.8l-0.2-0.1l0.2-0.6H717.1z M710.2,245.7l-0.1-0.3l-0.1,0.4L710.2,245.7L710.2,245.7z
                        M697.7,228l-0.4,0.3l-0.6,0.8l-0.2,0.7l0.2,0.2l1.6-0.9l0.4,0l0.5-0.1l0.1-0.7l-0.8-0.5L697.7,228z M695,227.5l-0.3-0.8
                        l-0.1-1.2l-0.3-0.3l-0.3-0.1l-1.4-1.9l-0.9,0.2l-1.1,2.7l0.3,1.2l1.1-0.3l0.5-0.3l0.1,2.2l-0.8,1.6l0.8,0.4l1.7,0.2l0.8-0.3
                        l-0.2-0.5l0-0.5l0.6-0.6l0.4-0.8l-0.4-0.4L695,227.5z M710.4,246.3l-0.2-0.4l-0.3,0l-0.2,0.1l-0.9,0.1l0.1,0.2l-0.2,0.1l0.6,0.7
                        l0,0h0l0,0l0.2,0h0.4l0,0l0.6-0.1l-0.3-0.5L710.4,246.3z M699.5,209.2l-1.3-0.3l-1.2,0.6l0,0l-0.2,0.1l-0.5,0.2L696,210l-1-0.3
                        l-0.3,0.2l0,0l0,0l-0.1,0.1l0.2,1l0.5,0.1l0,0l0.1,0.2l1.2-0.6l0.7-0.3l0.4,0l0.2-0.2l0.2-0.1L699.5,209.2L699.5,209.2
                        L699.5,209.2z"/>
                    </g>
                    
                  </g>
                </g>
                <g class="tw"></g>
              </svg>
              <img class="man" src="img/map-man.png" alt="">
              <img v-if="mapTab == 'map1'" class="legend" src="img/legend-map1.svg" alt="">
              <img v-if="mapTab == 'map2'" class="legend" src="img/legend-map2.svg" alt="">
            </div>
          </div>
        </div>

        <div class="filter">
          <div class="col">
            <div class="select county">
              <select v-model="filter.selectedCounty" @change="changeCounty">
                <option value="臺北市">臺北市</option>
                <option value="新北市">新北市</option>
                <option value="桃園市">桃園市</option>
                <option value="新竹縣">新竹縣</option>
                <option value="新竹市">新竹市</option>
                <option value="苗栗縣">苗栗縣</option>
                <option value="臺中市">臺中市</option>
                <option value="南投縣">南投縣</option>
                <option value="彰化縣">彰化縣</option>
                <option value="雲林縣">雲林縣</option>
                <option value="嘉義縣">嘉義縣</option>
                <option value="嘉義市">嘉義市</option>
                <option value="臺南市">臺南市</option>
                <option value="高雄市">高雄市</option>
                <option value="屏東縣">屏東縣</option>
                <option value="基隆市">基隆市</option>
                <option value="宜蘭縣">宜蘭縣</option>
                <option value="花蓮縣">花蓮縣</option>
                <option value="臺東縣">臺東縣</option>
                <option value="金門縣">金門縣</option>
                <option value="澎湖縣">澎湖縣</option>
                <option value="連江縣">連江縣</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="select town">
              <select v-model="filter.selectedTown" @change="updateTooltip">
                <option v-for="(item, idx) in townList" :value="item" :key="idx">{{item}}</option>
              </select>
            </div>
          </div>
        </div>

        <div class="tk-tooltip">
          <div class="title">客家人口小檔案</div>
          <div class="inner">
            <h3>{{tooltipData.name}}</h3>
            <table class="tk-table">
              <tr>
                <td>設籍人口</td>
                <td class="num1">{{tooltipData.amount}}</td>
              </tr>
              <tr>
                <td>設籍客家人口</td>
                <td class="num2">{{tooltipData.map1}}</td>
              </tr>
              <tr>
                <td>客家人口比例</td>
                <td class="num3">{{tooltipData.map2}}%</td>
              </tr>
            </table>
            <div class="unit">單位：千人</div>
          </div>
        </div>
      </section>

      <section class="sec-a" id="anchor-number">
        <div class="tk-container">
          <div class="big-title">
            <h2>
              <img src="img/chart-title.svg" alt="你所不知道的客家!">
            </h2>
            <div class="info">進一步分析全國466.9萬客家人口的性別比、居住地、年齡分布、腔調與客語聽說能力，可以勾勒出台灣客家族群人口數、分布、語言能力與認同情形。</div>
          </div>
          <div class="content">
            <div class="sec-desc">
              <div class="sec-title">
                <img src="img/title-a.svg" alt="">
                <div>
                  <span>A</span>數字看客家
                </div>
                <img class="man" src="img/chart-man.png" alt="">
              </div>
              <div class="text" v-if="chart1Tab == '族群'">調查結果顯示，全國約有466.9萬人符合《客家基本法》的客家人定義，約占台灣2,356.1萬人口的19.8%。</div>
              <div class="text" v-if="chart1Tab == '居住地'">全國466.9萬客家人之中，超過一半的客家人並沒有住在客庄。</div>
              <div class="text" v-if="chart1Tab == '性別'">調查報告推估，客家人口呈現男性略多於女性的情形，與國內目前女性（50.3%）略多於男性（49.7%）的比例略有差異。</div>
              <div class="tabs">
                <div :class="{tab: true, active: chart1Tab == '族群'}" @click="drawPie('族群', [80.2, 19.8])">族群</div>
                <div :class="{tab: true, active: chart1Tab == '居住地'}" @click="drawPie('居住地', [53.7, 46.3])">居住地</div>
                <div :class="{tab: true, active: chart1Tab == '性別'}" @click="drawPie('性別', [48.7, 51.3])">性別</div>
              </div>
            </div>
            <div class="chart-wrapper">
              <svg id="pie" viewbox="0 0 500 500"></svg>
            </div>
          </div>
        </div>
      </section>
    
      <section class="sec-b">
        <div class="tk-container">
          <div class="content">
            <div class="sec-desc">
              <div class="sec-title">
                <img src="img/title-a.svg" alt="">
                <div>
                  <span>B</span>客家人口小知識
                </div>
                <img class="man" src="img/chart-man.png" alt="">
              </div>
              <div class="text" v-if="chart2Tab == '縣市'">在年齡結構方面，客家民眾在0-39 歲、60-69 歲比率，相對較全國人口比例高；40-59 歲、70 歲以上人口比率則相對較全國人口比率低。</div>
              <div class="text" v-if="chart2Tab == '年齡'">在年齡結構方面，客家民眾在0-39 歲、60-69 歲比率，相對較全國人口比例高；40-59 歲、70 歲以上人口比率則相對較全國人口比率低。</div>
              <div class="text" v-if="chart2Tab == '腔調'">調查顯示，在可複選的情況下，有26.7%的客家民眾至少能使用兩種客語腔調。<br>客家民眾所使用且能分辨出來的客語腔調，仍是「四縣腔」的比率最高，其次為「海陸腔」。</div>
              <div class="tabs">
                <div :class="{tab: true, active: chart2Tab == '縣市'}" @click="drawChart2('縣市')">縣市</div>
                <div :class="{tab: true, active: chart2Tab == '年齡'}" @click="drawChart2('年齡')">年齡</div>
                <div :class="{tab: true, active: chart2Tab == '腔調'}" @click="drawChart2('腔調')">腔調</div>
              </div>
            </div>
            <div class="chart-wrapper">
              <div>
                <div class="canvas-wrapper75">
                  <div class="a" v-if="chart2Tab == '縣市'">客家全國總數：4,669,200人</div>
                  <div class="b" v-if="chart2Tab == '縣市'">占全國比例 19.82%</div>
                  <div class="y-unit y-unit1" v-if="chart2Tab == '縣市'">單位：千人</div>
                  <div class="y-unit y-unit2" v-else>單位：百分比</div>
                  <div class="legend" v-if="chart2Tab == '年齡'">
                    <div class="item">
                      <div class="rect" style="background: #dd467d;"></div>客家人口
                    </div>
                    <div class="item">
                      <div class="rect" style="background: #2c345c;"></div>全國人口
                    </div>
                  </div>
                  <canvas id="chart2"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    
      <section class="sec-c">
        <div class="tk-container">
          <div class="content">
            <div class="sec-desc">
              <div class="sec-title">
                <img src="img/title-a.svg" alt="">
                <div>
                  <span>C</span>客家人講客嗎?
                </div>
                <img class="man" src="img/chart-man.png" alt="">
              </div>
              <div class="text">
                依據調查，有56.4%的客家民眾能聽懂客語；可以流利使用客語的客家民眾僅38.3%。<br><br>
                調查顯示，客家民眾客語聽說能力，呈現年齡越高、學歷越低，越能流利使用客語的趨勢，且女性客語聽說能力略優於男性；客庄所在地的桃竹苗、高屏、花東，客語能力也普遍優於其他地區。
              </div>
              <div class="tabs">
                <div :class="{tab: true, active: chart3Tab == '年齡'}" @click="drawChart3('年齡')">年齡</div>
                <div :class="{tab: true, active: chart3Tab == '性別'}" @click="drawChart3('性別')">性別</div>
                <div :class="{tab: true, active: chart3Tab == '教育1' || chart3Tab == '教育2'}" @click="drawChart3('教育1')">教育</div>
                <div :class="{tab: true, active: chart3Tab == '地區'}" @click="drawChart3('地區')">地區</div>
              </div>
            </div>
            <div class="chart-wrapper">
              <div>
                <div class="canvas-wrapper60">
                  <canvas id="chart3"></canvas>
                  <div class="legend">
                    <div class="item">
                      <div class="rect" style="background: #3b4d1e;"></div>聽懂
                    </div>
                    <div class="item">
                      <div class="rect" style="background: #e6e1a1;"></div>會說（流利）
                    </div>
                  </div>
                </div>
                <div v-if="chart3Tab == '教育1' || chart3Tab == '教育2'" class="edu-tab">
                  <div :class="{tab: true, active: chart3Tab == '教育1'}" @click="drawChart3('教育1')">18歲以下</div>
                  <div :class="{tab: true, active: chart3Tab == '教育2'}" @click="drawChart3('教育2')">19歲以上</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Section News-->
    <section class="section-news section-charts" id="anchor-news">
      <div class="news-boxes container">
        <div class="row">
          <div class="col-12"><img class="section-title" src="img/news/title.png" alt="相關新聞"/></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/1.jpg)"></div>
              <div class="title">平名戰士／到底是神是鬼？義民信仰５大ＱＡ報你知！</div>
              <div class="title-line"></div>
              <div class="content-txt">適逢農曆7月20日義民節，《客新聞》梳理國立聯合大學客家研究學院林本炫院長訪談內容，並歸納為「５大常見義民信仰ＱＡ」，希望各界能瞭解何為義民精神，而非只將義民信仰視為單純的傳統祭祀活動。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/2.jpg)"></div>
              <div class="title">平民戰士／236年後的今天　「義民精神」帶給我們什麼意義？</div>
              <div class="title-line"></div>
              <div class="content-txt">這個源於1786年的故事，在236年後的今天能帶給我們的意義是什麼？《客新聞》特別訪問客籍學者，探究義民信仰背後「義民精神」的意義。談到何謂義民精神，學者們皆異口同聲表示，客家人敬拜的就是義民爺保鄉衛土精神！這也正是當今21世紀生活在自由民主台灣的我們必須反思的重要精神。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/3.jpg)"></div>
              <div class="title">圖輯／234年來最盛大義民祭開幕！推3砲臺炸響新竹蒼穹</div>
              <div class="title-line"></div>
              <div class="content-txt">距今236年前，一群桃竹苗地區、不分族群的無名英雄犧牲性命保衛家園，他們的精神受到後代景仰，《客新聞》今年以「平民戰士」專題，告訴大家這事件對於21世紀台灣的意義。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/4.jpg)"></div>
              <div class="title">平名戰士／到底是神是鬼？義民信仰５大ＱＡ報你知！</div>
              <div class="title-line"></div>
              <div class="content-txt">適逢農曆7月20日義民節，《客新聞》梳理國立聯合大學客家研究學院林本炫院長訪談內容，並歸納為「５大常見義民信仰ＱＡ」，希望各界能瞭解何為義民精神，而非只將義民信仰視為單純的傳統祭祀活動。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/5.jpg)"></div>
              <div class="title">平民戰士／236年後的今天　「義民精神」帶給我們什麼意義？</div>
              <div class="title-line"></div>
              <div class="content-txt">這個源於1786年的故事，在236年後的今天能帶給我們的意義是什麼？《客新聞》特別訪問客籍學者，探究義民信仰背後「義民精神」的意義。談到何謂義民精神，學者們皆異口同聲表示，客家人敬拜的就是義民爺保鄉衛土精神！這也正是當今21世紀生活在自由民主台灣的我們必須反思的重要精神。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/6.jpg)"></div>
              <div class="title">圖輯／234年來最盛大義民祭開幕！推3砲臺炸響新竹蒼穹</div>
              <div class="title-line"></div>
              <div class="content-txt">距今236年前，一群桃竹苗地區、不分族群的無名英雄犧牲性命保衛家園，他們的精神受到後代景仰，《客新聞》今年以「平民戰士」專題，告訴大家這事件對於21世紀台灣的意義。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/7.jpg)"></div>
              <div class="title">平名戰士／到底是神是鬼？義民信仰５大ＱＡ報你知！</div>
              <div class="title-line"></div>
              <div class="content-txt">適逢農曆7月20日義民節，《客新聞》梳理國立聯合大學客家研究學院林本炫院長訪談內容，並歸納為「５大常見義民信仰ＱＡ」，希望各界能瞭解何為義民精神，而非只將義民信仰視為單純的傳統祭祀活動。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/8.jpg)"></div>
              <div class="title">平民戰士／236年後的今天　「義民精神」帶給我們什麼意義？</div>
              <div class="title-line"></div>
              <div class="content-txt">這個源於1786年的故事，在236年後的今天能帶給我們的意義是什麼？《客新聞》特別訪問客籍學者，探究義民信仰背後「義民精神」的意義。談到何謂義民精神，學者們皆異口同聲表示，客家人敬拜的就是義民爺保鄉衛土精神！這也正是當今21世紀生活在自由民主台灣的我們必須反思的重要精神。</div></a></div>
          <div class="col-12 col-lg-4"><a class="news-box" href="#" target="_blank">
              <div class="img-box" style="background-image:url(img/news/9.jpg)"></div>
              <div class="title">圖輯／234年來最盛大義民祭開幕！推3砲臺炸響新竹蒼穹</div>
              <div class="title-line"></div>
              <div class="content-txt">距今236年前，一群桃竹苗地區、不分族群的無名英雄犧牲性命保衛家園，他們的精神受到後代景仰，《客新聞》今年以「平民戰士」專題，告訴大家這事件對於21世紀台灣的意義。</div></a></div>
        </div>
      </div>
    </section>
  </div>

  <footer class="footer section-charts">
    <div class="container">
      <div class="row align-items-lg-end justify-content-between">
        <div class="col-12 col-lg-9">
          <p>統籌｜莊勝鴻 </p>
          <p>執行｜李台源、蘇佑昇、宋佩遙、范修語、洪俊傑、任紫婕</p>
          <p>數位｜林昌明 </p>
          <p>視覺｜仨咖多媒體設計工作室 </p>
          <p>數據來源｜客家委員會「110年全國客家人口暨語言基礎資料調查研究」</p>
        </div>
        <div class="col-12 col-lg-3">   <img class="logo" src="img/logo.png" alt="客新聞"></div>
        <div class="col-12">
          <div class="footer-line"></div>
          <p class="txt">本次客委會調查由國立中央大學客家語文暨社會科學學系張翰璧教授帶領研究團隊及典通股份有限公司執行，採用市話調查方式，自2021年3月15日起至11月6日止，成功訪問63,111位受訪者，在信心水準95%下，抽樣誤差在±0.39%之間。並以2020年12月底內政部發布之鄉（鎮、市、區）戶籍統計人口數分布為依據，進行事後分層的加權推估，加權變數包含性別、年齡層、鄉鎮市區等變數，因此調查結果能推估全國人口的情況。 完整報告刊於客家委員會官網政府公開資訊專區。</p>
        </div>
      </div>
    </div>
  </footer>
  
  <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
  <script src="https://d3js.org/d3.v6.js"></script>
  <script src="https://unpkg.com/topojson@3"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/data.js?1223"></script>
  <script src="./js/all.js?1223"></script>
  <script src="js/map_chart.js?1223"></script>
</body>
</html>