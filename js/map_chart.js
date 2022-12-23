var vm = new Vue({
  el: '#map_chart',
  data: {
    topo: null,
    tooltip: null,
    mapTab: 'map1',
    townList: [],
    filter: {
      selectedCounty: '臺北市',
      selectedTown: '松山區',
    },
    tooltipData: {
      name: '',
      amount: '',
      map1: '',
      map2: '',
    },
    chart1Tab: '族群', // 族群 居住地 性別
    chart2Tab: '縣市', // 縣市 年齡 腔調
    chart2: null,
    chart3Tab: '年齡', // 年齡 性別 教育 地區
    chart3: null,
  },
  mounted() {
    var width = 345;
    var height = 530;
    // var tw = d3.select('#svg .tw');
    var self = this;
    d3.json('topo.json').then((data) => {
      self.topo = data;
      d3.select('#islands').style('display', 'block');
      self.updateMap('map1');
    });

    this.tooltip = d3.select("#map .tk-tooltip");
    this.changeCounty();
    // this.updateTooltip();
      
    var width = 500;
    var height = width;
    d3.select('#pie')
          .append('g')
          .attr("transform", `translate(${width / 2},${height / 2})`);
    this.drawPie('族群', [80.2, 19.8]);
    this.drawChart2('縣市');
    this.drawChart3('年齡');
    this.initSections();
  },
  methods: {
    changeCounty() {
      var selectedCounty = this.filter.selectedCounty;
      var mapData = JSON.parse(JSON.stringify(MAP_DATA));
      this.townList = mapData.filter(function(item){
        return item.county == selectedCounty;
      });
      this.townList = this.townList.map(function(item){
        return item.town;
      });
      this.filter.selectedTown = this.townList[0];

      this.updateTooltip();
    },
    updateTooltip() {
      var name = this.filter.selectedCounty+this.filter.selectedTown;
      var filter = MAP_DATA.filter(function(item){
        return item.name == name;
      });
      var data = filter.length > 0 ? filter[0] : null;
      this.tooltipData = {
        name: data.name,
        amount: data.amount,
        map1: data.map1,
        map2: data.map2,
      };
      this.tooltip.style("visibility", "visible");
    },
    updateMap(type) {
      this.mapTab = type;

      var counties = topojson.feature(this.topo, this.topo.objects.TOWN_MOI_1100415)
      var projection = d3.geoMercator().center([123, 23.7]).scale(8000);
      var path = d3.geoPath().projection;
      var self = this;

      d3.select('#svg .tw').selectAll('.geo-path')
        .data(counties.features)
        .join('path')
        .attr('class', 'geo-path')
        .attr('d', path(projection))
        .style('stroke', '#f0ce77')
        .style('fill', function(d){
          var name = d.properties.COUNTYNAME+d.properties.TOWNNAME;
          var filter = MAP_DATA.filter(function(item){
            return item.name == name;
          });
          var data = filter.length > 0 ? filter[0] : null;
          var color = 'red';
          if(data && type == 'map1'){
            if(data.map1 >= 0 && data.map1 <= 1) color = '#ffffff';
            else if(data.map1 > 1 && data.map1 <= 2) color = '#d9ead3';
            else if(data.map1 > 2 && data.map1 <= 5) color = '#b6d7a8';
            else if(data.map1 > 5 && data.map1 <= 10) color = '#93c47d';
            else if(data.map1 > 10 && data.map1 <= 20) color = '#6aa84f';
            else if(data.map1 > 20 && data.map1 <= 30) color = '#38761d';
            else if(data.map1 > 30 && data.map1 <= 50) color = '#274e13';
            else if(data.map1 > 50 && data.map1 <= 100) color = '#0c343d';
            else if(data.map1 > 100) color = '#000000';
          }
          else if(data && type == 'map2'){
            if(data.map2 >= 0 && data.map2 <= 3) color = '#ffffff';
            else if(data.map2 > 3 && data.map2 <= 5) color = '#ead1dc';
            else if(data.map2 > 5 && data.map2 <= 10) color = '#d5a6bd';
            else if(data.map2 > 10 && data.map2 <= 15) color = '#c27ba0';
            else if(data.map2 > 15 && data.map2 <= 20) color = '#a64d79';
            else if(data.map2 > 20 && data.map2 <= 30) color = '#741b47';
            else if(data.map2 > 30 && data.map2 <= 60) color = '#4c1130';
            else if(data.map2 > 60) color = '#000000';
          }
          return color;
        })
        .on('mouseover', function(e, d){
          var name = d.properties.COUNTYNAME+d.properties.TOWNNAME;
          var filter = MAP_DATA.filter(function(item){
            return item.name == name;
          });
          var data = filter.length > 0 ? filter[0] : null;

          self.filter.selectedCounty = d.properties.COUNTYNAME;
          self.changeCounty();
          self.filter.selectedTown = d.properties.TOWNNAME;
          
          self.tooltipData = {
            name: data.name,
            amount: data.amount,
            map1: data.map1,
            map2: data.map2,
          }

          self.tooltip.style("visibility", "visible");
        })
        .on("mousemove", function(e){
          self.tooltip
            .style("top", (e.clientY + 10)+"px")
            .style("left", (e.clientX + 10)+"px")
        })
        .on('mouseleave', function(e){
          // d3.select(this).style('stroke', 'none')
          if( self.tooltip.style('position') === 'fixed' ){
            self.tooltip.style("visibility", "hidden");
          }
        })

      var islands = [
        {id: 'Matsu_Islands', name: '連江縣'},
        {id: 'Kinmen', name: '金門縣'},
        {id: 'Penghu', name: '澎湖縣'},
      ]
      for(var i=0; i<islands.length; i++){
        var island = islands[i];
        setIsland(island);
      }
      
      function setIsland(island) {
        d3.select('#' + island.id + ' path')
          .style('stroke', '#000')
          .style('stroke-width', '0.3px')
          .style('fill', function(d){
            var name = island.name;
            var filter = MAP_DATA.filter(function(item){
              return item.name == name;
            });
            var data = filter.length > 0 ? filter[0] : null;
            var color = 'red';
            if(data && type == 'map1'){
              if(data.map1 >= 0 && data.map1 <= 1) color = '#ffffff';
              else if(data.map1 > 1 && data.map1 <= 2) color = '#d9ead3';
              else if(data.map1 > 2 && data.map1 <= 5) color = '#b6d7a8';
              else if(data.map1 > 5 && data.map1 <= 10) color = '#93c47d';
              else if(data.map1 > 10 && data.map1 <= 20) color = '#38761d';
              else if(data.map1 > 20 && data.map1 <= 50) color = '#38761d';
              else if(data.map1 > 50 && data.map1 <= 100) color = '#274e13';
              else if(data.map1 > 100) color = '#000000';
            }
            else if(data && type == 'map2'){
              if(data.map2 >= 0 && data.map2 <= 3) color = '#ffffff';
              else if(data.map2 > 3 && data.map2 <= 5) color = '#ead1dc';
              else if(data.map2 > 5 && data.map2 <= 10) color = '#d5a6bd';
              else if(data.map2 > 10 && data.map2 <= 15) color = '#c27ba0';
              else if(data.map2 > 15 && data.map2 <= 20) color = '#a64d79';
              else if(data.map2 > 20 && data.map2 <= 30) color = '#741b47';
              else if(data.map2 > 30 && data.map2 <= 60) color = '#4c1130';
              else if(data.map2 > 60) color = '#000000';
            }
            return color;
          })
          
        d3.select('#' + island.id)
          .on('mouseover', function(e, d){
            var name = island.name;
            var filter = MAP_DATA.filter(function(item){
              return item.name == name;
            });
            var data = filter.length > 0 ? filter[0] : null;
            
            self.tooltipData = {
              name: data.name,
              amount: data.amount,
              map1: data.map1,
              map2: data.map2,
            }
            
            self.tooltip.style("visibility", "visible");
          })
          .on("mousemove", function(e){
            self.tooltip
              .style("top", (e.clientY + 10)+"px")
              .style("left", (e.clientX + 10)+"px")
          })
          .on('mouseleave', function(e){
            // d3.select(this).style('stroke', 'none')
            self.tooltip.style("visibility", "hidden");
          })
      }
    },
    drawPie(type, data) {
      this.chart1Tab = type;

      var width = 500;
      var height = width;
      var radius = Math.min(width, height) / 2;
      var color = ["#b67474", "#e3a9a5"];
      var g = d3.select('#pie g');

      var pie = d3.pie()
        .sort(null);
      var data_ready = pie(data)
      var arcGenerator = d3.arc()
          .innerRadius(100)
          .outerRadius(250);

      g.selectAll("path")
        .data(data_ready)
        .join('path')
        .attr('fill', (d, idx) => color[idx])
        .transition()
        .ease(d3.easeLinear)
        .duration(600)
        .delay(0)
        .attrTween('d', function(d) {
          var i = d3.interpolate(d.startAngle+0.1, d.endAngle);
          return function(t) {
            d.endAngle = i(t);
            return arcGenerator(d);
          }
        });

      g
        .selectAll('text')
        .data(data_ready)
        .join('text')
        .text(function(d, idx){ 
          var pre = '';
          if(type == '族群') {
            if(idx == 0) pre = '非客家人';
            else if(idx == 1) pre = '客家人';
          } else if(type == '居住地') {
            if(idx == 0) pre = '非客庄';
            else if(idx == 1) pre = '客庄';
          } else if(type == '性別') {
            if(idx == 0) pre = '女性';
            else if(idx == 1) pre = '男性';
          }
          
          return pre + ' ' + d.data + '%'
        })
        .attr('fill', '#000')
        .attr("transform", function(d) { return `translate(${arcGenerator.centroid(d)})`})
        .style("text-anchor", "middle")
        .style("font-size", '25px')
    },
    drawChart2(type) {
      this.chart2Tab = type;

      if(this.chart2) this.chart2.destroy();

      if(type == '縣市') {
        this.chart2 = new Chart(document.getElementById('chart2'), {
          type: 'bar',
          data: {
            labels: BAR1.map(row => row['地區']),
            datasets: [{
              label: '',
              data: BAR1.map(row => row['人口數']),
              // backgroundColor: '#22b7c9',
              backgroundColor: ['#2c345c', '#2c345c', '#2c345c', '#2c345c', '#dd467d', '#2c345c', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d', '#dd467d'], 
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/75,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                displayColors: false,
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '(千人)';
                  }
                }
              }
            },
            interaction: {
              intersect: false
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  callback: function(value, index, ticks) {
                    return BAR1[index]['地區'].split('');
                  },
                  autoSkip: false,
                  maxRotation: 0,
                  minRotation: 0,
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '年齡') {
        this.chart2 = new Chart(document.getElementById('chart2'), {
          type: 'bar',
          data: {
            labels: BAR2.map(row => row['年齡']),
            datasets: [{
              label: '客家人口',
              data: BAR2.map(row => row['客家人']),
              backgroundColor: '#dd467d',
            },{
              label: '全國人口',
              data: BAR2.map(row => row['全國人口']),
              backgroundColor: '#2c345c',
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/75,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  // callback: function(value, index, ticks) {
                  //   return BAR1[index]['地區'].split('');
                  // },
                  // autoSkip: false,
                  // maxRotation: 0,
                  // minRotation: 0,
                  // // font: {
                  // //   size: 16
                  // // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                suggestedMax: 20,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '腔調') {
        this.chart2 = new Chart(document.getElementById('chart2'), {
          type: 'bar',
          data: {
            labels: BAR3.map(row => row['label']),
            datasets: [{
              label: '',
              data: BAR3.map(row => row['value']),
              backgroundColor: ['#2c345c', '#5c7b71', '#5c7b71', '#5c7b71', '#5c7b71', '#5c7b71', '#5c7b71', '#5c7b71'],
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/75,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                displayColors: false,
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  // callback: function(value, index, ticks) {
                  //   return BAR1[index]['地區'].split('');
                  // },
                  // autoSkip: false,
                  // maxRotation: 0,
                  // minRotation: 0,
                  // // font: {
                  // //   size: 16
                  // // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
    },

    drawChart3(type) {
      this.chart3Tab = type;

      if(this.chart3) this.chart3.destroy();

      if(type == '年齡') {
        this.chart3 = new Chart(document.getElementById('chart3'), {
          type: 'bar',
          data: {
            labels: BAR4.map(row => row['年齡']),
            datasets: [{
              label: '聽懂',
              data: BAR4.map(row => row['聽懂']),
              backgroundColor: '#3b4d1e',
            },{
              label: '會說（流利）',
              data: BAR4.map(row => row['會說']),
              backgroundColor: '#e6e1a1',
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/60,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  // callback: function(value, index, ticks) {
                  //   return BAR1[index]['地區'].split('');
                  // },
                  // autoSkip: false,
                  // maxRotation: 0,
                  // minRotation: 0,
                  // // font: {
                  // //   size: 16
                  // // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '性別') {
        this.chart3 = new Chart(document.getElementById('chart3'), {
          type: 'bar',
          data: {
            labels: BAR5.map(row => row['性別']),
            datasets: [{
              label: '聽懂',
              data: BAR5.map(row => row['聽懂']),
              backgroundColor: '#3b4d1e',
            },{
              label: '會說（流利）',
              data: BAR5.map(row => row['會說']),
              backgroundColor: '#e6e1a1',
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/60,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  // callback: function(value, index, ticks) {
                  //   return BAR1[index]['地區'].split('');
                  // },
                  // autoSkip: false,
                  // maxRotation: 0,
                  // minRotation: 0,
                  // // font: {
                  // //   size: 16
                  // // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '教育1') {
        this.chart3 = new Chart(document.getElementById('chart3'), {
          type: 'bar',
          data: {
            labels: EDU1.map(row => row['教育程度']),
            datasets: [{
              label: '聽懂',
              data: EDU1.map(row => row['聽懂']),
              backgroundColor: '#3b4d1e',
            },{
              label: '會說（流利）',
              data: EDU1.map(row => row['會說']),
              backgroundColor: '#e6e1a1',
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/60,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                },
                ticks: {
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '教育2') {
        this.chart3 = new Chart(document.getElementById('chart3'), {
          type: 'bar',
          data: {
            labels: EDU2.map(row => row['教育程度']),
            datasets: [{
              label: '聽懂',
              data: EDU2.map(row => row['聽懂']),
              backgroundColor: '#3b4d1e',
            },{
              label: '會說（流利）',
              data: EDU2.map(row => row['會說']),
              backgroundColor: '#e6e1a1',
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/60,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                },
                ticks: {
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
      else if(type == '地區') {
        this.chart3 = new Chart(document.getElementById('chart3'), {
          type: 'bar',
          data: {
            labels: BAR7.map(row => row['地區']),
            datasets: [{
              label: '聽懂',
              data: BAR7.map(row => row['聽懂']),
              backgroundColor: '#3b4d1e',
            },{
              label: '會說（流利）',
              data: BAR7.map(row => row['會說']),
              backgroundColor: '#e6e1a1',
            }]
          },
          options: {
            // animation: {
            //   duration: 0
            // },
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 100/60,
            plugins: {
              legend: {
                display: false,
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItems, data) { 
                    return tooltipItems.formattedValue + '%';
                  }
                }
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              x: {
                grid: {
                  display: false
                },
                ticks: {
                  // callback: function(value, index, ticks) {
                  //   return BAR1[index]['地區'].split('');
                  // },
                  // autoSkip: false,
                  // maxRotation: 0,
                  // minRotation: 0,
                  // // font: {
                  // //   size: 16
                  // // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size,
                      lineHeight: 1.1
                    };
                  }
                },
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: true,
                  drawOnChartArea: false,
                  // color: '#fff'
                },
                ticks: {
                  // font: {
                  //   size: 16
                  // }
                  font: function(context) {
                    var width = context.chart.width;
                    var size = Math.round(width / 35);
                    return {
                      size: size
                    };
                  }
                }
              }
            }
          }
        });
      }
    },
    initSections() {
      $('.section-maps').addClass('section-charts')
    }
  },
})