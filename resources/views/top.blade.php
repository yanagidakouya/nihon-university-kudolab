<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>太陽光発電監視システム</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <div id="app" class="app">

    <div class="inner m-0-auto">
      <div>
        <div class="d-flex justify-content-center align-items-center w-100">
          <div 
            class="w-50 pt-1 pb-1 text-center border-tab pointer"
            v-on:click="getConditions('1')"
            v-bind:class="{' tab-active': isActive === '1', 'tab-disable': isActive === '2'}"
          >
          全期間
          </div>
          <div
            class="w-50 pt-1 pb-1 text-center border-tab pointer"
            v-on:click="getConditions('2')"
            v-bind:class="{' tab-active': isActive === '2', 'tab-disable': isActive === '1'}"
          >
          日付
          </div>
        </div>

        <div class="chart__container">
          <div class="chart__box">
            <canvas id="chart"></canvas>
          </div>
        </div>
  
        <div v-if="isActive === '2'">
          <div class="d-flex justify-content-space-around pt-2">

            <div class="w-50 text-center">
              <input type="date" name="date_start" class="conditions conditions__date" id="dateOnly" v-on:change="getConditions(isActive)" v-model="filterDate">
            </div>
            <div id="ElectricityType" class="text-white w-50 text-center">
              <input type="radio" class="conditions ml-2 mr-1" v-on:change="getConditions(isActive)" name="type" value="power" checked>Power
              <input type="radio" class="conditions ml-2 mr-1" v-on:change="getConditions(isActive)" name="type" value="voltage">Volatge
              <input type="radio" class="conditions ml-2 mr-1" v-on:change="getConditions(isActive)" name="type" value="current">Current
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
  <script src="{{ asset('assets/js/common.js') }}"></script>
  <script>
    new Vue({
      el: '#app',
      data: {
        xLabel: null,
        panel_1: null, //光触媒あり
        panel_2: null, //光触媒なし
        isActive: '1',
        filterDate: new Date().toISOString().slice(0,10)
      },

      mounted: async function() {
        await this.getPowerGenerationData('ini');//　初期チェックは電力
      },

  
      methods: {

        changeTab: async function (num) {
          this.isActive = num;
          if(typeof myChart !== 'undefined' && myChart) {
            myChart.destroy();//new chartでグラフがいっぱい描写されてしまうのでリセット
          }
          this.canvasChart();
        },
        


        canvasChart: function() {
          var chart = document.getElementById('chart');
          window.myChart = new Chart(chart, {
            type: 'line',
            data: {
              labels: this.xLabel,
              datasets: [
                {
                  label: '光触媒あり',
                  data: this.panel_1,
                  fill: false,
                  borderColor: '#FDC167',
                },
                {
                  label: '光触媒なし',
                  data: this.panel_2,
                  fill: false,
                  borderColor: '#105170',
                },
              ]
            },
            options: {
              animation: {
                duration: 0,
              },
              hover: {
                animationDuration: 0, // アイテムのマウスオーバー時のアニメーションの長さ
              },
              // events: ['click'],
              scales: {
                xAxes: [{
                  gridLines: {
                    display: true,
                    color: 'rgba(255, 255, 255, 0.075)',
                  }
                }],
                yAxes: [{
                  gridLines: {
                    display: true,
                    color: 'rgba(255, 255, 255, 0.075)',
                  }
                }]
              }
            }
          });

        },

        // DBからデータをjson形式で取得
        getPowerGenerationData: async function(param, ElectricityType) {
          // プロットのリセット
          this.panel_1 = [];
          this.panel_2 = [];
          this.xLabel = [];
          var count_1 = 0;
          var count_2 = 0;
          var daily_count = 0;
          const hours = [
            '0時','1時','2時','3時','4時','5時',
            '6時','7時','8時','9時','10時','11時',
            '12時','13時','14時','15時','16時','17時',
            '18時','19時','20時','21時','22時','23時',
          ]

          if(this.isActive == "1") {
            var api = '/api/get_power_generation?ini=1&' ;
          } else {
            var api = '/api/get_power_generation?ini=0&' + param ;
          }
            
          await axios
            .get(api)
            .then( res => {
              // 光触媒あり
              for(var data_1 in res.data.panel_1) {
                if(ElectricityType == 'power'){
                  this.panel_1.push( res.data.panel_1[count_1].power );
                } else if (ElectricityType == 'voltage') {
                  this.panel_1.push( res.data.panel_1[count_1].voltage );
                } else if (ElectricityType == 'current') {
                  this.panel_1.push( res.data.panel_1[count_1].current );
                }
                count_1 = count_1 + 1;
              }

              // 光触媒なし
              for(var data_2 in res.data.panel_2) {
                if(ElectricityType == 'power'){
                  this.panel_2.push( res.data.panel_2[count_2].power );
                } else if (ElectricityType == 'voltage') {
                  this.panel_2.push( res.data.panel_2[count_2].voltage );
                } else if (ElectricityType == 'current') {
                  this.panel_2.push( res.data.panel_2[count_2].current );
                }
                count_2 = count_2 + 1;
              }

              // 期間なら
              for (var day in res.data.daily) {
                this.panel_1.push( res.data.daily[daily_count].panel_1_max_power );
                this.panel_2.push( res.data.daily[daily_count].panel_2_max_power );
                this.xLabel.push( res.data.daily[daily_count].date );
                daily_count = daily_count + 1;
              }

              if(this.isActive == '2') {
                this.xLabel = hours;
              }
            })
            .catch( err => { console.log(err); })

            if(typeof myChart !== 'undefined' && myChart) {
              myChart.destroy();//new chartでグラフがいっぱい描写されてしまうのでリセット
            }
            this.canvasChart();

        },

        // 絞込み条件が変化したら
        getConditions: async function(num) {
          await this.changeTab(num)
          var type = document.getElementsByName('type'); // ラジオボタン取得
          
          for ( var selectedType = "", i = type.length; i--; ) { 
            if ( type[i].checked ) {
              var ElectricityType = type[i].value; //選択値
              break ;
            }
          }

          if(this.isActive === '1') {
            var param = '&max_power=1' + '&type=' + ElectricityType;
            
          } else if(this.isActive === '2') {
            var dateOnly  = document.getElementById('dateOnly').value;
            var param = 'date_only=' + dateOnly + '&type=' + ElectricityType;
          }

          if(typeof myChart !== 'undefined' && myChart) {
              myChart.destroy();//new chartでグラフがいっぱい描写されてしまうのでリセット
          }
          this.getPowerGenerationData(param, ElectricityType);
          this.canvasChart();

        }
      }
    });
    </script>
</body>
</html>