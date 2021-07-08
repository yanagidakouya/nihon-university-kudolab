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
  <div id="app"  class="inner m-0-auto">
    <div>
      <p>絞り込み</p>
      <div>
        <select name="yearStart" id="yearStart" class="conditions" v-on:change="getConditions">
          <option value="2020">2020</option>
          <option value="2021">2021</option>
          <option value="2022">2022</option>
        </select>
        年
        <select name="monthStart" id="monthStart" class="conditions" v-on:change="getConditions">
          @for ($month = 1; $month < 13; $month++)
            <option value="{{ $month }}">{{ $month }}</option>
          @endfor
        </select>
        月

        <select name="dayStart" id="dayStart" class="conditions" v-on:change="getConditions">
          @for ($day = 1; $day < 32; $day++)
            <option value="{{ $day }}">{{ $day }}</option>
          @endfor
        </select>
        日
        〜
        <select name="yearEnd" id="yearEnd" class="conditions" v-on:change="getConditions">
          <option value="2020">2020</option>
          <option value="2021">2021</option>
          <option value="2022">2022</option>
        </select>
        年
        <select name="monthEnd" id="monthEnd" class="conditions" v-on:change="getConditions">
          @for ($month = 1; $month < 13; $month++)
            <option value="{{ $month }}">{{ $month }}</option>
          @endfor
        </select>
        月
        <select name="dayEnd" id="dayEnd" class="conditions" v-on:change="getConditions">
          @for ($day = 1; $day < 32; $day++)
            <option value="{{ $day }}">{{ $day }}</option>
          @endfor
        </select>
        日
      </div>
    </div>
    <div id="ElectricityType">
      <input type="radio" class="conditions" v-on:change="getConditions" name="type" value="power" checked>電力
      <input type="radio" class="conditions" v-on:change="getConditions" name="type" value="voltage">電圧
      <input type="radio" class="conditions" v-on:change="getConditions" name="type" value="current">電流
    </div>
    <div class="chart__container">
      <div class="chart__box">
        <canvas id="chart"></canvas>
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
        date: [
          "'19年1月", "'19年2月", "'19年3月", "'19年4月",
          "'19年1月", "'19年2月", "'19年3月", "'19年4月",
          "'19年1月", "'19年2月", "'19年3月", "'19年4月",
          "'19年1月", "'19年2月", "'19年3月", "'19年4月",
          "'19年1月", "'19年2月", "'19年3月", "'19年4月",
        ],
        panel_1: null, //光触媒あり
        panel_2: null, //光触媒なし
      },

      mounted: async function() {
        await this.getPowerGenerationData('ini');//　初期チェックは電力
      },

      methods: {
        canvasChart: function() {
          var chart = document.getElementById('chart');
          var myChart = new Chart(chart, {
          type: 'line',
            data: {
                labels: this.date,
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
            }
          });
        },

        // DBからデータをjson形式で取得
        getPowerGenerationData: async function(param, ElectricityType) {
          // プロットのリセット
          this.panel_1 = [];
          this.panel_2 = [];
          var count_1 = 0;
          var count_2 = 0;
          if(param == 'ini') {
            var api = '/api/get_power_generation?ini=1&' ;
            await axios
              .get(api)
              .then( res => {

                // 光触媒あり
                for(var data_1 in res.data.panel_1) {
                  this.panel_1.push( res.data.panel_1[count_1].power );
                  count_1 = count_1 + 1;
                }

                // 光触媒なし
                for(var data_2 in res.data.panel_2) {
                  this.panel_2.push( res.data.panel_2[count_2].power );
                  count_2 = count_2 + 1;
                }
              })
              .catch( err => { console.log(err); })
            
          } else {
            var api = '/api/get_power_generation?ini=0&' + param ;
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
              })
              .catch( err => { console.log(err); })
          }
          this.canvasChart();
        },

        // 絞込み条件が変化したら
        getConditions: function() {
          var yearStart  = document.getElementById('yearStart').value;
          var monthStart = document.getElementById('monthStart').value;
          var dayStart   = document.getElementById('dayStart').value;
          var yearEnd    = document.getElementById('yearEnd').value;
          var monthEnd   = document.getElementById('monthEnd').value;
          var dayEnd     = document.getElementById('dayEnd').value;
          var type       = document.getElementsByName('type'); // ラジオボタン取得
          for ( var selectedType = "", i = type.length; i--; ) { 
            if ( type[i].checked ) {
              var ElectricityType = type[i].value; //選択値
              break ;
            }
          }
          var param = 'year_start=' + yearStart + '&month_start=' + monthStart + '&day_start=' + dayStart + '&year_end=' + yearEnd + '&month_end=' + monthEnd + '&day_end=' + dayEnd + '&type=' + ElectricityType;

          this.getPowerGenerationData(param, ElectricityType);
          this.canvasChart();
        }
      }
    });
    </script>
</body>
</html>