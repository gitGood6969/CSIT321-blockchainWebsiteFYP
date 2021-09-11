pragma solidity >=0.4.22 <0.7.0;

import "./ConvertLib.sol";


contract STIContract {
	mapping (address => uint) balances;

	event Transfer(address indexed _from, address indexed _to, uint256 _value);

	constructor(uint AccBalance) public {
		balances[tx.origin] = AccBalance;
	
	}
	
	function setBalance(address accountOf,uint bal)public{
		balances[accountOf] = bal;

	}
	
	function sendCoin(address receiver,address sender, uint amount) public returns(bool sufficient) {
		if (balances[sender] < amount) return false;
		balances[sender] -= amount;
		balances[receiver] += amount;
		emit Transfer(sender, receiver, amount);
		return true;
	}

	function getBalanceInEth(address addr) public view returns(uint){
		return ConvertLib.convert(getBalance(addr),2);
	}

	function getBalance(address addr) public view returns(uint) {
		return balances[addr];
	}
}
