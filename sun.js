// 假設用戶輸入的匯率和金額存儲在以下對象中
const purchases = [
    { rate: 1.5, amount: 100 },
    { rate: 1.6, amount: 200 },
    { rate: 1.7, amount: 300 },
  ];
  
  // 計算所有外幣購買金額的總和
  const totalAmount = purchases.reduce((sum, purchase) => sum + purchase.amount, 0);
  
  // 計算所有外幣購買金額的加權平均購買匯率
  const weightedSum = purchases.reduce((sum, purchase) => sum + purchase.rate * purchase.amount, 0);
  const averageRate = weightedSum / totalAmount;
  
  console.log(`加權平均購買匯率為：${averageRate}`);
  