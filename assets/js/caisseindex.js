const ctx = document.getElementById('myChart');
let data = JSON.parse(ctx.getAttribute('solde'));
const months = ['Jan','Fev','Mars','Avril', 'Mai','Juin','Juil','Août','Sept','Oct', 'Nov','Dec']
let depots= [0,0,0,0,0,0,0,0,0,0,0,0];
let retraits= [0,0,0,0,0,0,0,0,0,0,0,0];
for (let i = 0; i < data.length; i++) {
  depots[data[i].month-1] = data[i].depot;
  retraits[data[i].month-1] = data[i].retrait;
}

console.log(data)



console.log(depots)

new Chart(ctx, {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: '# Dépôts',
      data: depots,
      color:'#ff00ff',
      borderWidth: 3,
      borderColor: '#36a2eb',
      backgroundColor: '#36a2eb44',
      fill: true,
      tension: 0.1,
    },{
      label: '# Retrait',
      data: retraits,
      color:'#ff00ff',
      borderWidth: 3,
      borderColor: '#f66183',
      backgroundColor: '#f6618344',
      fill: true,
      tension: 0.1,
    }
    ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

