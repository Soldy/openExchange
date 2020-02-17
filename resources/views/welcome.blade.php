<!DOCTYPE html>
<html>
    <head>
          <style type='text/css'> 
               html{
                   font: 13px arial, sans-serif; 
               }
               body { 
                   color: white;
                   background-color: #ddd; 
               }
               h1 { 
                   font: bold 20px arial;
                   text-align:center;
                   width:100%;
                   margin: 0 0 10px;
                }
               .col, .col3 { 
                   display: inline-block;
                   float:left;
                   padding:5px 0;
               }
               .col {
                   width:90px; 
               }
               .col3 {
                   width:180px; 
               }
               .col2 { 
                   display: inline-block; 
                   float:left; 
                   padding:5px 0;
                }
               div.newrow {
                   clear: both;
                   line-height: 1.8em;
               }
               button {
                   display:inline-block;
                   margin-right:30px;
                   padding:6px 10px;
                   background-color:#eee;
               }
               button{
                   width:100%;
               }
               input, select {
                   height: 18px;
                   padding: 0;
                   margin: 0;
                   border: 0;
               }
               select {
                   width:170px;
               }
               input { 
                   width:80px;
               }
               div.readonly {
                   display:inline-block;
                   border:0px solid white;
                   border-radius:0px;
                   padding:0;
                   line-height:1.5em;
                   text-align : center;
                   font-size:20px;
                   font-weight:200;
               }
               .line {
                   display:static;
                   width:100%;
                   height:40px;
               }
               .exchangebox {
                   position: absolute;
                   top: 50px; 
                   left:50px; 
                   width:450px;
                   padding:40px;
                   background-color:#424bf5;
                }
          </style>
          <script>
              const calc = function(){
                  let amt    = document.getElementById('amount').value;
                  if (1>amt) amt =1;
                  let from_c = document.getElementById('formCurrency').value;
                  let to_c   = document.getElementById('toCurrency').value;
                  let url    = "/api/exchange/"+amt+"/"+from_c+"/"+to_c;
                  fetch(url)
                      .then( async function (response) {
                      return await response.json();
                  })
                     .then((myJson) => {
                     document.getElementById("result").innerHTML=myJson.amount;
                  });
              }
              const currencies = { 
                   'CAD':"CAD - Canadian Dollar",
                   'CHF':"CHF - Swiss Dollar",
                   'EUR':"EUR - Euro",
                   'GBP':"GBP - British Pound",
                   'HKD':"HKD - Hong Kong Dollar",
                   'JPY':"JPY - Japanese Yen",
                   'RUB':"RUB - Russian Ruble1",
                   'THB':"THB - Thai Bhat",
                   'USD':"USD - American Dollar"
              }
              const currencyListMaker = function(id, target){
                  let select = document.createElement("select");
                  select.setAttribute("id", id);
                  for(let i in currencies){
                      let option = document.createElement('option');
                      option.value=i;
                      option.appendChild(document.createTextNode(currencies[i]));
                      select.appendChild(option);
                  }
                  return document.getElementById(target).appendChild(select)


              }
              window.onload =function(){
                  currencyListMaker('formCurrency', 'formHolder')
                  currencyListMaker('toCurrency', 'toHolder')
                  document.getElementById('calc').addEventListener("click", calc); 
              }
          </script>
         <title>currency exchange</title>
    </head>
    <body>
        <div class="exchangebox" id="outline">
            <div class="line">
                <h1>currency exchange</h1>
            </div>
            <div class="line">
                <div class='col'>
                    <input type='text' id='amount'>
                </div>
            <div class='col3' id="formHolder">
            </div>
            <div class='col3' id="toHolder">
            </div>
            </div>
            <div class='line readonly' id='result'></div> 
            <div class="line">
                <button id='calc'>Calculate</button> 
            </div>
        </div>
    </body>
</html>
