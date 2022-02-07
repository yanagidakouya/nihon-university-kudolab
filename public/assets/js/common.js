new Vue({
  el: '#app',
  data: {
    xLabel: null,
    panel_1: null, //光触媒厚塗り
    panel_2: null, //光触媒薄塗り
    isActive: '1',
    filterDate: new Date().toISOString().slice(0,10)
  },

  mounted: async function() {
    await this.getPowerGenerationData();
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
              label: '光触媒厚塗り',
              data: this.panel_1,
              fill: false,
              borderColor: '#FDC167',
            },
            {
              label: '光触媒薄塗り',
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
              },
              ticks: {                      //最大値最小値設定
                min: 0,                   //最小値
                max: 8,                  //最大値
              },
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

      if(this.isActive == "1") {
        var api = '/api/get_power_generation?ini=1&' ;
      } else {
        var api = '/api/get_power_generation?ini=0&' + param ;
      }
        
      await axios
        .get(api)
        .then( res => {
          // 光触媒厚塗り
          var res = res.data;
          for(var data_1 in res.panel_1) {
            if(ElectricityType == 'power'){
              this.panel_1.push( res.panel_1[count_1].power );
            } else if (ElectricityType == 'voltage') {
              this.panel_1.push( res.panel_1[count_1].voltage );
            } else if (ElectricityType == 'current') {
              this.panel_1.push( res.panel_1[count_1].current );
            }
            count_1 = count_1 + 1;
          }

          // 光触媒薄塗り
          for(var data_2 in res.panel_2) {
            if(ElectricityType == 'power'){
              this.panel_2.push( res.panel_2[count_2].power );
            } else if (ElectricityType == 'voltage') {
              this.panel_2.push( res.panel_2[count_2].voltage );
            } else if (ElectricityType == 'current') {
              this.panel_2.push( res.panel_2[count_2].current );
            }
            this.xLabel.push( this.dateFormat(res.panel_2[count_2].created_at) )
            count_2 = count_2 + 1;
          }

          // 期間なら
          for (var day in res.daily) {
            this.panel_1.push( res.daily[daily_count].panel_1_max_power );
            this.panel_2.push( res.daily[daily_count].panel_2_max_power );
            this.xLabel.push( res.daily[daily_count].date );
            daily_count = daily_count + 1;
          }
        })
        .catch( err => { console.log(err); })

        if(typeof myChart !== 'undefined' && myChart) {
          myChart.destroy();//new chartでグラフがいっぱい描写されてしまうのでリセット
        }
        this.xLabel.sort(function(a, b){
          return (a > b ? 1 : -1);
        });
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

    },

    dateFormat: function (date) {
      date = date.split(' ');
      date = date[1].substring(0, 5);
      return date;
    }
  }
});