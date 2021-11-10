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
          時間ごとの最大出力（日）
          </div>
        </div>

        <div class="chart__container">
          <div class="chart__box">
            <canvas id="chart"></canvas>
          </div>
        </div>

        <div id="export_csv" class="export_csv">
          <div class="export__csv__btn__box">
            <a href="/csv/panel_1" class="export__csv__btn" target="_blank">光触媒ありcsv</a>
            <a href="/csv/panel_2" class="export__csv__btn" target="_blank">光触媒なしcsv</a>
            <a href="/csv/by_day_power" class="export__csv__btn" target="_blank">日別最大電力csv</a>
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
</body>
</html>