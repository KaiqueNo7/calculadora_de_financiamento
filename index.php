<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calculadora de financiamento</title>
</head>
<style>
.form-calc {
  border-radius: 10px;
  padding: 20px;
  box-shadow: var(--sombra);
  background: var(--laranja);
}

.form-calc p {
  font-size: 1em;
  font-weight: 500;
  color: var(--branco);
}

.form-calc span {
  font-size: 0.9em;
  font-weight: 400;
  color: var(--branco);
  padding: 0 10px;
}

.form-calc h2 {
  font-size: 2em;
  font-weight: 600;
  color: var(--branco);
}

.form-calc label {
  font-size: 1em;
  font-weight: 500;
  color: var(--branco);
  padding: 0 10px;
}

.inputCalc {
  display: flex;
  flex-direction: column;
  text-align: left;
  color: var(--cinza);
  margin: 10px 0;
}

.inputCalc input, 
.inputCalc select {
  margin: 5px 10px 5px 0px;
  padding: 10px 20px;
  border-radius: 13px;
  border: 1px solid #fff7f7;
  box-shadow: var(--sombra);
  transition: 0.3s;
}

.inputCalc input:focus, 
.inputCalc select:focus {
  color: var(--laranja-claro);
  font-weight: 600;
}

.btn-calc {
  padding: 10px 20px;
  border-radius: 13px;
  border: 1px solid #fff7f7;
  background: var(--laranja);
  color: var(--branco);
  font-weight: 600;
  box-shadow: var(--sombra);
  cursor: pointer;
  transition: 0.3s;
  margin-bottom: 16px;
}

.btn-calc:hover {
  color: var(--laranja);
  background: var(--branco);
}

.js-center
{
  position: relative;
  display: flex;
  justify-content: center;
}

.js-btw
{
  position: relative;
  display: flex;
  justify-content: space-between;
}

.al-center
{
  align-items: center;
}

.al-start
{
  align-items: flex-start;
}

.al-end
{
  align-items: flex-end;
}

.column
{
  flex-direction: column;
}

.w100 { width:100%; }
.w25 { width:25%; }
</style>
<body>
<section class="js-center al-center column p0-50">
  <div class="js-center form-calc w100 column">
    <form method="post">
      <h2>Simule o seu financiamento</h2>
      <p>Preencha os campos a baixo e simule as parcelas do seu imóvel:</p>
      <div class="w100 js-btw al-end mbl-column">
        <div class="inputCalc w25">
          <label>Valor do imóvel:</label><span>Taxa de 7.9% sobre o imóvel</span>
          <input type="text" name="valor" placeholder="R$ 000.000,000" id="valor">
        </div>
        <div class="inputCalc w25">
          <label>Valor de entrada:</label>
          <input type="text" name="entrada" placeholder="R$ 000.000,000" id="entrada">
        </div>
        <div class="inputCalc w25">
          <label>Tempo do financiamento:</label>
          <select name="numParcelas" id="numParcelas">
            <option value="">Selecione uma opção</option>
            <option value="120">10 anos</option>
            <option value="240">20 anos</option>
            <option value="360">30 anos</option>
          </select>
        </div>
        <button class="btn-calc w25" type="button" name="calcular" value="calular" id="calular" onclick="calcularFin();">Calcular</button>
      </div>
    </form>
    </div>
    <div class="js-center al-center w100">
      <canvas id="myChart"></canvas>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
  $(document).ready(function() {
    $('#valor').maskMoney({
      prefix: 'R$ ',
      thousands: '.',
      decimal: ','
    });
  });

  $(document).ready(function() {
    $('#entrada').maskMoney({
      prefix: 'R$ ',
      thousands: '.',
      decimal: ','
    });
  });
</script>
<script type="text/javascript">
function calcularFin() {

  var valor = $('#valor').maskMoney('unmasked')[0];
  var entrada = $('#entrada').maskMoney('unmasked')[0];
  var numParcelas = parseFloat($('#numParcelas').val());

  valor = parseFloat(valor);
  entrada = parseFloat(entrada);

  //console.log(valor);
  //console.log(entrada);
  //console.log(numParcelas);

  var valorParcela = [];
  var juros = [];
  var amortizacoes = [];
  var saldosDevedores = [];
  var datas = [];
  var taxa = 0.75;
  var financiamento = valor - entrada;
  var amortizacao = financiamento / numParcelas;
  var saldoDevedor = financiamento;

  for (var i = 0; i < numParcelas; i++) {
    var jurosPeriodo = ((saldoDevedor * taxa) / 100);

    console.log(jurosPeriodo);

    var parcelas = amortizacao + jurosPeriodo;
    saldoDevedor -= amortizacao;

    console.log(parcelas);

    amortizacoes.push(amortizacao.toFixed(2));
    juros.push(jurosPeriodo.toFixed(2));
    saldosDevedores.push(saldoDevedor.toFixed(2));

    valorParcela.push(parseFloat(parcelas.toFixed(2))); // Converter para número novamente

    var data = new Date(); // Use a data correta aqui
    data.setMonth(data.getMonth() + i);
    var dataFormatada = data.toLocaleDateString('pt-BR', { month: 'numeric', year: 'numeric' });
    datas.push(dataFormatada);
  }

  renderizarGrafico(datas, valorParcela);
}

function renderizarGrafico(datas, valorParcela) {
  var ctx = document.getElementById('myChart');
  var chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: datas,
      datasets: [{
        label: 'Parcelas',
        data: valorParcela,
        borderColor: 'rgba(251, 133, 0, 1)',
        backgroundColor: 'rgba(251, 133, 0, 1)',
        borderWidth: 1
      }]
    },
    options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Simulação das parcelas do imóvel *Importante ressaltar que é apenas um exemplo de como pode ficar as parcelas do financiamento.*'
      }
    }
  },
  });
}
</script>
</body>
</html>        
