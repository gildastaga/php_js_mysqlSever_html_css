$(function () {
    get_data();
    init();
});
function get_data() {
    function recupSelection(src, dest)
    {
        var valeur = src.options[src.selectedIndex].value;
        console.log(valeur);
        if (valeur = '')
            return;

        dest.value += src.options[src.selectedIndex].value + 'n';
        src.selectedIndex = 0;
    }
}    
//function get_data(src) {
//    $('#period').submit({
//        
//    });
//    var t = document.getElementById("#number").value;
//    var nbre=document.getElementById('number').value;
//    var periode=document.getElementById('period').value;
//    alert("valeur"+t);
//    console.log(t);
//    //alert(periode);


function init() {
    $.get("user/starts", function (data) {
        var tab = jQuery.parseJSON(data);
        getStats(tab);
    });
}

function getStats(data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(u => u.UserName),
            datasets: [{
                    label: '# of Votes',
                    data: data.map(u => parseInt(u.activity)),

                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        }
    });
}

