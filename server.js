var net = require('net');
var http = require('http');
var express = require('express');
var app = express();
const { exec } = require("child_process");
fpath = require('path').dirname(require.main.filename)
var path = require('path');
var net = require('net');
var Promise = require('bluebird');
var cluster = require('cluster');
const fs = require('fs');
const solc = require('solc');
const Web3 = require('web3');
var crypto = require('crypto');
var mysql = require('mysql');
var router = express.Router();
"use strict";
var dateTime = require('node-datetime');
var dt = dateTime.create();
var http = require('http');
var url = require('url');
var WebsocketServer = require('websocket').server;
var myContract = require('./build/contracts/STIContract.json');//JSON FILE OF CONTRACT
var Accounts = require('web3-eth-accounts');
var WebsocketServer = require('websocket').server;
const web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
var textToEncrypt = new Date().toISOString().substr(0,19) + '|My super secret information.';
var encryptionMethod = 'AES-256-CBC';
var secret = "My32charPasswordAndInitVectorStr"; //must be 32 char length
var iv = secret.substr(0,16);


// This is your real test secret API key.
const stripe = require("stripe")("sk_test_51J5RBqKtvF4rDCc7NHJxgxAuFNzJGo1LhsxIWvKMW7QIy0cHPsbjYoYvwLWEQFrxphQDUlR21YEcxijlxLz8dp2200okGl9ahO");

app.use(express.static("."));
app.use(express.json());

let server =  app.listen(process.env.PORT || 4242);


const CreditCardPayIn = async(TransactionName,Amount,UID) =>{
	Amount = Math.round(Amount* 100);
	app.set('Amount', Amount);
	app.set('UID', UID);	
	app.post(TransactionName, async (req, res) => {
	  const { items } = req.body;
	  // Create a PaymentIntent with the order amount and currency
	  const paymentIntent = await stripe.paymentIntents.create({
		amount: app.get('Amount'),
		currency: "sgd"
	  });

	  res.send({
		clientSecret: paymentIntent.client_secret,
		UID: app.get('UID'),
		Link:"https://01d3f4096b3e.ngrok.io"
	  });
	});


	
}

const CreditCardPayOut = async(TransactionName,Amount,UID) =>{
	app.set('Amount', Amount);
	app.all(TransactionName, function func(req, res) {
	var expressaccount = '';
	const createAccountFunc = async ()=> {
		const account = await stripe.accounts.create({
		type: 'express',
	});
		console.log(account.id)
		expressaccount = account.id;
	}
	const sendFunc = async ()=> {
		const accountLinks = await stripe.accountLinks.create({
			account: expressaccount,
			refresh_url: 'https://01d3f4096b3e.ngrok.io/ConvertPage.php',
			return_url: 'https://01d3f4096b3e.ngrok.io/CreditCardPayOutController.php',
			type: 'account_onboarding',
		});
		res.send({"RESPONSE":accountLinks.url,"ACCOUNT":expressaccount,"AMOUNT":app.get('Amount')});
	}
	createAccountFunc().then(() => sendFunc());
});
let server =  app.listen(process.env.PORT || 3020);
		setTimeout(function(){ server.close()}, 1000);	
	
}




web3.eth.net.isListening().then(() => console.log('CONNECTED TO GANACHE')).catch(e => console.log('NOT CONNECTED TO GANACHE'));
// Variables


var decrypt = function (encryptedMessage, encryptionMethod, secret, iv) {
    var decryptor = crypto.createDecipheriv(encryptionMethod, secret, iv);
    return decryptor.update(encryptedMessage, 'base64', 'utf8') + decryptor.final('utf8');
};


const checkSTICOINBal = async (Acc_address)=>{
			
	//Gather contract details
	const id = await web3.eth.net.getId();
	const deployednetwork = myContract.networks[id];
	const contractAddress = deployednetwork.address;
	const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
	var balance = 0;

	const Balance = await STIContract.methods.getBalance(Acc_address).call().then(function(result) {
	 balance = result;

	});

	return balance;
}
const checkWalletBal = async (Acc_address)=>{

  var balance = 0;
  await web3.eth.getBalance(Acc_address, function(err, result) {
  if (err) {
    console.log(err)
  } else {
     balance = result;
	
	 	
  }
})
	return balance;

}



const checkAccountInNetwork= async(TransactionName,AccountPubKey) =>{

	app.set('AccountPublicKey', AccountPubKey);
		
	app.all(TransactionName, function func(req, res) {
		var accountexist =  web3.utils.isAddress(app.get('AccountPublicKey'));
		console.log(accountexist);
		res.send({"RESPONSE":accountexist});
		app.use(function(req, res, next) {
		delete req.headers[TransactionName]; // should be lowercase
		next();
});

	
});

	let server =  app.listen(process.env.PORT || 3001);
	setTimeout(function(){ server.close()}, 1000);	
	


	
}
const TopUPSTIC_Response = async (TransactionName,Amount,EscrowPrivateKey,STICPubKey,StripeTxID)=> {
	app.set('Amount', Amount);
	app.set('EscrowPrivate', EscrowPrivateKey);
	app.set('STICPubKey', STICPubKey);	
	app.set('StripeTxID', StripeTxID);
	await app.all(TransactionName, function (req, res) {
			
		const sendFunc = async ()=> {
			

				
				var STICAmount = parseFloat(app.get('Amount'));
				//Gather contract details
				const id = await web3.eth.net.getId();
				const deployednetwork = myContract.networks[id];
				const contractAddress = deployednetwork.address;
				const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
				console.log(`Making a call to contract at address: ${contractAddress}`);
				var PrevBalance = await STIContract.methods.getBalance(app.get('STICPubKey')).call().then(function(result) {
					const converttowei = async(result)=>{
					STICAmount+=parseFloat(result);
					}
					converttowei(result);
					

			});
			const STIContract_init_Tx = await STIContract.methods.setBalance(app.get('STICPubKey'),String(toFixed(STICAmount)));
			//Create tranasaction
			const InitTransaction = await web3.eth.accounts.signTransaction(
			{
			to: contractAddress,
			data: STIContract_init_Tx.encodeABI(),
			gas: await STIContract_init_Tx.estimateGas(),
			},
			app.get('EscrowPrivate')
			);
			const InitReciept = await web3.eth.sendSignedTransaction(
			InitTransaction.rawTransaction);
			console.log(InitReciept);
			setTimeout(function(){ UpdateTransaction(InitReciept.transactionHash,app.get('Amount'),'Stripe:'+app.get('StripeTxID'),app.get('STICPubKey'),app.get('EscrowPrivate'),'Top-Up')}, 1000);
			res.send({"Transaction":"Success"});
			}
		sendFunc();
		
});

let server =  app.listen(process.env.PORT || 3002);
setTimeout(function(){ server.close()}, 1000);
}
const RedeemSTIC_Response = async (TransactionName,Amount,AccountNumber,EscrowPrivate,STICPubKey)=> {
	
	Amount = Math.round(Amount* 100);
	app.set('Amount', Amount);
	app.set('AccountNumber', AccountNumber);
	app.set('EscrowPrivate', EscrowPrivate);
	app.set('STICPubKey', STICPubKey);	

	app.all(TransactionName, function (req, res) {
			
		const sendFunc = async ()=> {

				const transfer = await stripe.transfers.create({
				amount: app.get('Amount'),
				currency: "sgd",
				destination: app.get('AccountNumber'),
				});

				var STICAmount = Math.round(new Number(app.get('Amount')));
				
				//Gather contract details
				const id = await web3.eth.net.getId();
				const deployednetwork = myContract.networks[id];
				const contractAddress = deployednetwork.address;
				const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
				console.log(`Making a call to contract at address: ${contractAddress}`);
				var PrevBalance = await STIContract.methods.getBalance(app.get('STICPubKey')).call().then(function(result) {
				const converttowei = async(result)=>{
					
					STICAmount= Math.round(new Number(result))-STICAmount;

				}
				converttowei(result);
				
				
			});
			const STIContract_init_Tx = await STIContract.methods.setBalance(app.get('STICPubKey'),String(toFixed(STICAmount)));
		
			const InitTransaction = await web3.eth.accounts.signTransaction(
			{
			to: contractAddress,
			data: STIContract_init_Tx.encodeABI(),
			gas: await STIContract_init_Tx.estimateGas(),
			},
			app.get('EscrowPrivate')
			);
			
			const InitReciept = await web3.eth.sendSignedTransaction(
			InitTransaction.rawTransaction);
			console.log(InitReciept);
			res.send({"Transaction":"Success"});
			setTimeout(function(){UpdateTransaction(InitReciept.transactionHash,app.get('Amount'),app.get('STICPubKey'),app.get('AccountNumber'),app.get('EscrowPrivate'),'Redeem')}, 1000);
			}
		
			
		sendFunc();
});

let server =  app.listen(process.env.PORT || 3003);
setTimeout(function(){ server.close()}, 1000);
}

function getAvailablePort(startingAt) {

    function getNextAvailablePort (currentPort, cb) {
        const server = net.createServer()
        server.listen(currentPort, _ => {
            server.once('close', _ => {
                cb(currentPort)
            })
            server.close()
        })
        server.on('error', _ => {
            getNextAvailablePort(++currentPort, cb)
        })
    }

    return new Promise(resolve => {
        getNextAvailablePort(startingAt, resolve)
    })
}



const createNewAccount = async(TransactionName) => {
	app.get(TransactionName, function (req, res,jsondata) {
	const sendFunc = async ()=> {
	var account = web3.eth.accounts.create();
	res.send({"pubkey":account.address,"privatekey":account.privateKey});
	}
	sendFunc();
	});
	let server =  app.listen(process.env.PORT || 3004);
	setTimeout(function(){ server.close()}, 1000);

	
}

const PayForProduct_Response = async (TransactionName,Title,Amount,EscrowPrivate,EscrowPublic,UserPublic)=> {
	
Amount = toFixed(Math.round(Amount* 100));
app.set('Amount', Amount);
app.set('Title', Title);
app.set('UserPubKey', UserPublic);
app.set('EscrowPubkKey', EscrowPublic);	
app.set('EscrowPrivateKey', EscrowPrivate);
app.all(TransactionName, function (req, res) {	
const sendFunc = async ()=> {
	const id = await web3.eth.net.getId();
	const deployednetwork = myContract.networks[id];
	const contractAddress = deployednetwork.address;
	const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
	console.log(`Making a call to contract at address: ${contractAddress}`);
	const STICTransaction = STIContract.methods.sendCoin(app.get('EscrowPubkKey'),app.get('UserPubKey'),String(app.get('Amount')));
	const InitTransaction = await web3.eth.accounts.signTransaction(
	{
		to: contractAddress,
		data: STICTransaction.encodeABI(),
		gas: await STICTransaction.estimateGas()+10000,
	},
	app.get('EscrowPrivateKey')
	);
	const InitReciept = await web3.eth.sendSignedTransaction(InitTransaction.rawTransaction);
	console.log(InitReciept.transactionHash);
	console.log("Successfully Transferred");
	res.send({"Transaction":"Success"});
	setTimeout(function(){ UpdateTransaction(InitReciept.transactionHash,app.get('Amount'),app.get('UserPubKey'),app.get('EscrowPubkKey'),app.get('EscrowPrivateKey'),Title)}, 1000);
	const STIContract_init_Tx = await STIContract.methods.setBalance(app.get('EscrowPubkKey'),'0');
	const InitTransaction2 = await web3.eth.accounts.signTransaction(
	{
	to: contractAddress,
	data: STIContract_init_Tx.encodeABI(),
	gas: await STIContract_init_Tx.estimateGas()+10000,
	},
	app.get('EscrowPrivateKey')
	);
	const InitReciept2 = await web3.eth.sendSignedTransaction(InitTransaction2.rawTransaction);
	console.log(InitReciept2.transactionHash);
	console.log("Payment Successful");

}					
sendFunc();

});

let server =  app.listen(process.env.PORT || 3004);
setTimeout(function(){ server.close()}, 1000);

}

const Buyer_Refund_Response = async (TransactionName,ContractID,SellerPubKey,BuyerPubKey,EscrowPrivateKey,Amount)=> {
InitAmount = Amount;
Amount = toFixed(Math.round(Amount* 100));
const id = await web3.eth.net.getId();
const deployednetwork = myContract.networks[id];
const contractAddress = deployednetwork.address;
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
console.log(`Making a call to contract at address: ${contractAddress}`);
const STICTransaction = await STIContract.methods.sendCoin(BuyerPubKey,SellerPubKey,String(Amount));
const InitTransaction = await web3.eth.accounts.signTransaction(
{
	to: contractAddress,
	data: STICTransaction.encodeABI(),
	gas: await STICTransaction.estimateGas()+10000,
},
EscrowPrivateKey
);
const InitReciept = await web3.eth.sendSignedTransaction(InitTransaction.rawTransaction).then(function(result) {
console.log(result);
UpdateTransaction(result.transactionHash,Amount,SellerPubKey,BuyerPubKey,EscrowPrivateKey,'Refund Transaction');
var con = mysql.createConnection({
host: "localhost",
user: "root",
password: "",
database: "sticdb"
});
const date = dt.format('d-m-Y');
const date2 = dt.format('Y-m-d');
const connectfunction = async()=>{
 con.query("SELECT * FROM contracts  WHERE `ContractID`= '"+ContractID+"'", function (err, sqlresult, fields) {
    if (err) throw err;
	newdata = [result.transactionHash,date,InitAmount,sqlresult[0].BuyerUserID,sqlresult[0].SellerUserID];
	arr = JSON.parse(sqlresult[0].TransactionID)
	arr.push(newdata);
	arr= JSON.stringify(arr);
	con.query("UPDATE `contracts` SET `TransactionID`= '"+arr+"',`Status`='Refunded Transaction' ,`Paid`='refunded',`Transaction`='Complete',`TransactionCloseDate`='"+date2+"' WHERE `ContractID`= '"+ContractID+"'", function (err, sqlresult, fields) {
				if (err) throw err;})
		console.log(arr)
  });
}
connectfunction();

console.log("Updating Transactions");
setTimeout(function(){ con.destroy()}, 3000);
});



setTimeout(function(){
const updateStatusFunc = async ()=> {
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
const STICTransaction2 = await STIContract.methods.updateRefund(ContractID);
const InitTransaction2 = await web3.eth.accounts.signTransaction(
{
	to: contractAddress,
	data: STICTransaction2.encodeABI(),
	gas: await STICTransaction2.estimateGas()+10000,
},
EscrowPrivateKey
);
const InitReciept2 = await web3.eth.sendSignedTransaction(InitTransaction2.rawTransaction);
console.log(InitReciept2);
}
updateStatusFunc();
}, 2000);



}
const Contract_Payment_Response = async (TransactionName,ContractID,SellerPubKey,BuyerPubKey,EscrowPrivateKey,Amount)=> {
InitAmount = Amount;
Amount = toFixed(Math.round(Amount* 100));
const id = await web3.eth.net.getId();
const deployednetwork = myContract.networks[id];
const contractAddress = deployednetwork.address;
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
console.log(`Making a call to contract at address: ${contractAddress}`);
const STICTransaction = await STIContract.methods.sendCoin(SellerPubKey,BuyerPubKey,String(Amount));
const InitTransaction = await web3.eth.accounts.signTransaction(
{
	to: contractAddress,
	data: STICTransaction.encodeABI(),
	gas: await STICTransaction.estimateGas()+10000,
},
EscrowPrivateKey
);
const InitReciept = await web3.eth.sendSignedTransaction(InitTransaction.rawTransaction).then(function(result) {
console.log(result);
UpdateTransaction(result.transactionHash,Amount,BuyerPubKey,SellerPubKey,EscrowPrivateKey,'Contract Transaction');
var con = mysql.createConnection({
host: "localhost",
user: "root",
password: "",
database: "sticdb"
});
const date = dt.format('d-m-Y');

const connectfunction = async()=>{
 con.query("SELECT * FROM contracts  WHERE `ContractID`= '"+ContractID+"'", function (err, sqlresult, fields) {
    if (err) throw err;
	newdata = [result.transactionHash,date,InitAmount,sqlresult[0].BuyerUserID,sqlresult[0].SellerUserID];
	arr = JSON.parse(sqlresult[0].TransactionID)
	arr.push(newdata);
	arr= JSON.stringify(arr);
	con.query("UPDATE `contracts` SET `TransactionID`= '"+arr+"' WHERE `ContractID`= '"+ContractID+"'", function (err, sqlresult, fields) {
				if (err) throw err;})
		console.log(arr)
  });
}
connectfunction();

console.log("Updating Transactions");
setTimeout(function(){ con.destroy()}, 3000);
});



setTimeout(function(){
const updateStatusFunc = async ()=> {
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
const STICTransaction2 = await STIContract.methods.updatePaid(ContractID,String(Amount));
const InitTransaction2 = await web3.eth.accounts.signTransaction(
{
	to: contractAddress,
	data: STICTransaction2.encodeABI(),
	gas: await STICTransaction2.estimateGas()+10000,
},
EscrowPrivateKey
);
const InitReciept2 = await web3.eth.sendSignedTransaction(InitTransaction2.rawTransaction);
console.log(InitReciept2);
}
updateStatusFunc();
}, 2000);




}
/////////////////////////////////////////////////////////////////
const UpdateTransaction = async (Hash,Amount,Sender,Reciever,PrivKey,Title)=> {
var con = await mysql.createConnection({
host: "localhost",
user: "root",
password: "",
database: "sticdb"
});

const connectfunction = async()=>{
con.query("INSERT INTO `transactions`(`TransactionID`, `Receiver`, `Sender`, `Title` ,`Amount` ) VALUES ('"+Hash+"','"+ Reciever+"','"+Sender+"','"+Title+"','"+Amount+"')", function (err, result, fields) {
if (err) throw err;
;
});
}
connectfunction();
setTimeout(function(){ con.destroy()}, 3000);
}

/////////////////////////////////////////////////////////////////
const InitContract_Response = async (ContractID,ProductID,Amount,EscrowPrivate,Buyer,Seller)=> {
	console.log(EscrowPrivate)
const id = await web3.eth.net.getId();
const deployednetwork = myContract.networks[id];
const contractAddress = deployednetwork.address;
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
console.log(`Making a call to contract at address: ${contractAddress}`);
const STICTransaction = STIContract.methods.InitContract(ContractID,ProductID,(Amount*100),Buyer,Seller);

const InitTransaction = await web3.eth.accounts.signTransaction(
{
to: contractAddress,
data: STICTransaction.encodeABI(),
gas: await STICTransaction.estimateGas()+10000,
},
String(EscrowPrivate)
);

const InitReciept = await web3.eth.sendSignedTransaction(InitTransaction.rawTransaction);
console.log(InitReciept);
console.log("Contract Initialised");	
	
}

////////////////////////////////////////////////////////////////
const Get_ContractDetails_Response = async (TransactionName,ContractID)=> {
app.set('ContractID', ContractID);
app.all(TransactionName, function (req, res) {	
const sendFunc = async ()=> {
const id = await web3.eth.net.getId();
const deployednetwork = myContract.networks[id];
const contractAddress = deployednetwork.address;
const STIContract = new web3.eth.Contract(myContract.abi,deployednetwork.address);
var returnarr = {};

await STIContract.methods.getContractProductID(ContractID).call().then(function(result) {
	returnarr["ProductID"] = result;
}); 

await STIContract.methods.getContractBuyer(ContractID).call().then(function(result) {
	returnarr["Buyer"] = result;
});

await STIContract.methods.getContractSeller(ContractID).call().then(function(result) {
	returnarr["Seller"] = result;
});

await STIContract.methods.getContractAmount(ContractID).call().then(function(result) {
	returnarr["Amount"] = result;
});
await STIContract.methods.getContractStatus(ContractID).call().then(function(result) {
	returnarr["Status"] = result;
});

await STIContract.methods.getContractPaid(ContractID).call().then(function(result) {
	returnarr["Paid"] = result;
});

console.log(returnarr);
res.send(returnarr);
}					
sendFunc();
});

let server =  app.listen(process.env.PORT || 3070);
setTimeout(function(){ server.close()}, 1000);
}

////////////////////////////////////////////////////////////////
const ServerFunction = async () => {

var Server = await net.createServer(function(Sock) {
    console.log('Client Connected.');
    Sock.on('data',async  function(data) {
       console.log('Data received: ' + data);
		const JSONdata = JSON.parse(data);

      if(JSONdata.REQUEST == "GetNewAccount"){
			setTimeout(function(){createNewAccount('/GetNewAccount')},0);			
	   }
	  else if(JSONdata.REQUEST == "TopUpSTIC"){
			var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv);
			await setTimeout(function(){TopUPSTIC_Response('/TopUpSTIC',JSONdata.AMOUNT,decryptedMessage,JSONdata.PUBKEY,JSONdata.TXID)},1000);			
			
			
	   }
	    else if(JSONdata.REQUEST == "RedeemSTIC"){
			var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv);
			await setTimeout(function(){RedeemSTIC_Response('/RedeemSTIC',JSONdata.AMOUNT,JSONdata.ACCOUNT,decryptedMessage,JSONdata.PUBKEY)},1000);			

			
	   }
	  else if(JSONdata.REQUEST == "CheckAccount"){
			await setTimeout(function(){ checkAccountInNetwork('/CheckAccount',JSONdata.PUBKEY)},0);			
	  }
	  else if(JSONdata.REQUEST == "ContractPayment"){
	
		    var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv)
			await setTimeout(function(){ Contract_Payment_Response('/TransferAmount',JSONdata.CONTRACTID,JSONdata.SELLERPUBLICKEY,JSONdata.BUYERPUBLICKEY,decryptedMessage,JSONdata.AMOUNT)},4000);
		
	  }
	   else if(JSONdata.REQUEST == "PayForProduct"){
		  
			var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv);
			await setTimeout(function(){ PayForProduct_Response('/PayForProduct',JSONdata.TITLE,JSONdata.AMOUNT,decryptedMessage,JSONdata.ESCROWPUBLIC,JSONdata.PUBKEY)},1000);	
	   }
	   else if(JSONdata.REQUEST == "InitContract"){
			var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv);
			await setTimeout(function(){ InitContract_Response(JSONdata.CONTRACTID,JSONdata.PRODUCTID,JSONdata.AMOUNT,decryptedMessage,JSONdata.BUYERPUBLICKEY,JSONdata.SELLERPUBLICKEY)},1000);	
	   }
	  else if(JSONdata.REQUEST == "RefundBuyer"){
			var decryptedMessage = decrypt(JSONdata.ESCROWPRIVATE, encryptionMethod, secret, iv)
			await setTimeout(function(){ Buyer_Refund_Response('/TransferAmount',JSONdata.CONTRACTID,JSONdata.SELLERPUBLICKEY,JSONdata.BUYERPUBLICKEY,decryptedMessage,JSONdata.AMOUNT)},1000);
	  }
	  else if(JSONdata.REQUEST == "ContractInformation"){
			await setTimeout(function(){ Get_ContractDetails_Response('/GetContractInfo',JSONdata.CONTRACTID)},100);
	  }
	   else if(JSONdata.REQUEST == "CreditCardPayIn"){
		   
			await setTimeout(function(){ CreditCardPayIn('/create-payment-intent',JSONdata.AMOUNT,JSONdata.UserID)},100);
	  }
	   else if(JSONdata.REQUEST == "CreditCardPayOut"){
		   
			await setTimeout(function(){ CreditCardPayOut('/PayOut',JSONdata.AMOUNT,JSONdata.UserID)},100);
	  }
	   else{
		   console.log("Problem");
	   }
 
    });

	Sock.on('end', function(){
	console.log('Client Disconnected.'); });
	Sock.pipe(Sock);
});

Server.listen(8080, function() {
   console.log('Listening on port ' + 8080); 

});
 
}


const Server_ContractFunction = async () => {


var server = http.createServer(function(request,response) {
	function getPostParams(request, callback) {
	    var qs = require('querystring');
	    if (request.method == 'POST') {
	        var body = '';

	        request.on('data', function (data) {
	            body += data;
	            if (body.length > 1e6)
	                request.connection.destroy();
	        });

	        request.on('end', function () {
	            var POST = qs.parse(body);

				var array = JSON.parse(POST.data);
				
				
	            callback(POST);
	        });
	    }
	}
    // in-server request from PHP
    if (request.method === "POST") {
    	getPostParams(request, function(POST) {
			var array = JSON.parse(POST.data);
			if (typeof array.REQUEST != 'undefined') {
				
			}
			if (typeof array.Balance != 'undefined') {	
			
				var sticoinsbal;
				
				const getbalancefunc=async()=>{
					sticoinsbal = await checkSTICOINBal(array.PubKey);
					sticoinsbal = sticoinsbal/100;
					return sticoinsbal;
				}
				const sendfunc=async()=>{
					array.Balance =  await getbalancefunc();
					setTimeout(function(){
					POST.data =  JSON.stringify(array);
					messageClients(POST.data);
					response.writeHead(200);
					response.end();},1000);
				}
				sendfunc();
				
				
				
			}
			else{
				messageClients(POST.data);
				response.writeHead(200);
				response.end();
			}
			
			
		});
		return;
	}
});
server.listen(3030);
var websocketServer = new WebsocketServer({
	httpServer: server
});
websocketServer.on("request", websocketRequest);
// websockets storage
global.clients = {}; // store the connections
var connectionId = 0; // incremental unique ID for each connection (this does not decrement on close)
function websocketRequest(request) {
	// start the connection
	var connection = request.accept(null, request.origin); 
	connectionId++;
	// save the connection for future reference
	clients[connectionId] = connection;
	
}
// sends message to all the clients
function messageClients(message) {
	for (var i in clients) {
		clients[i].sendUTF(message);
	}
}
}
function toFixed(x) {
  if (Math.abs(x) < 1.0) {
    var e = parseInt(x.toString().split('e-')[1]);
    if (e) {
        x *= Math.pow(10,e-1);
        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
    }
  } else {
    var e = parseInt(x.toString().split('+')[1]);
    if (e > 20) {
        e -= 20;
        x /= Math.pow(10,e);
        x += (new Array(e+1)).join ('0');
    }
  }
  return x;
}



setInterval(
function(){
var con = mysql.createConnection({
host: "localhost",
user: "root",
password: "",
database: "sticdb"
});
const date = Date.parse(dt.format('Y-m-d'));

const connectfunction = async()=>{
 con.query("SELECT * FROM product", function (err, result, fields) {
    if (err) throw err;
	for(var x = 0;x<result.length;x++){
			if(date > Date.parse(result[x].DateOfExpiry)){
				con.query("UPDATE `product` SET `Status` = 'Unlisted' Where `ProductID` = '"+result[x].ProductID+"'", function (err, result, fields) {
				if (err) throw err;
	
				;
				})
			}
	}

  });
}
connectfunction();

console.log("Updating Product List");
setTimeout(function(){ con.destroy()}, 3000);
}, 3600000);
ServerFunction ();
Server_ContractFunction();
console.log(dt.format('Y-m-d H:M:S'));


