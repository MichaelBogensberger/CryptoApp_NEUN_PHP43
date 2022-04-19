import logo from './logo.svg';
import './App.css';

import * as React from 'react';

import {Button, Grid, Box, Paper, AppBar, Toolbar,Typography, IconButton, InputLabel,
MenuItem, FormControl, Select, TextField, Stack, Chip, List, ListItem, ListItemText, ListItemIcon, Divider, Avatar, ListItemAvatar
} from '@mui/material';

import {format} from 'date-fns';



import MenuIcon from '@mui/icons-material/Menu';
import PriceCheckIcon from '@mui/icons-material/PriceCheck';
import ImageIcon from '@mui/icons-material/Image';
import WorkIcon from '@mui/icons-material/Work';
import BeachAccessIcon from '@mui/icons-material/BeachAccess';
import React2, { useState, useEffect } from 'react';


function App() {

    const [walletSum, setWalletSum] = React.useState(0);

    const [currency, setCurrency] = React.useState(0);
    const [wallet, setWallet] = React.useState();
    const [amount, setAmount] = React.useState(0);

   
  
    const handleChange = (tag) => (event) => {

      if(tag == 'currency') {
        setCurrency(event.target.value);
      } else if(tag == 'wallet') {
        setWallet(event.target.value);
      } else if(tag == 'amount') {
        setAmount(event.target.value);
      }

    };

    const [wallets, setWallets] = useState([]);


    const url = 'http://localhost:8081/NEUN/php43_crypto/server/api';



      useEffect(() => {
        apiGetAllWallets();
      }, [])

 
      function apiGetAllWallets() {
        fetch(url + `/wallet/`)
          .then(res => res.json())
          .then(
            (result) => {
              //console.log(result);
              setWallets(result);

              var sum = 0;
              result.forEach((item, index)=>{
                sum = sum + item.price;
              })
              setWalletSum(sum);

            }
          )
      }


      async function kaufen() {
        
        var now = new Date();
        now =  format(now, 'yyyy-MM-dd hh:mm:ss');

  
          const rawResponse = await fetch(url + '/purchase', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'Access-Control-Allow-Origin': '*',
              'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,OPTIONS',
              'Access-Control-Allow-Headers': 'Content-Type'
            },
            body: JSON.stringify(
              {
                "date": now,
                "amount": Number(amount),
                "price": currency,
                "wallet_id": wallet
              }
            )
          });


          const content = await rawResponse.json();
          apiGetAllWallets();

          console.log(content);

      }



  return (
    <div className="App">

 


    <Box sx={{ flexGrow: 1 }}>
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            Crypto App
          </Typography>
        </Toolbar>
      </AppBar>
    </Box>



      
      <Box sx={{ flexGrow: 1 }}>

      <Grid container className="main-box" spacing={8}>
        <Grid item md={2}>
        </Grid>

        <Grid item md={4}>
          <Paper className="main-grid" elevation={24}>

          <h1 className="main-grid-ueb">Kaufen</h1>


          <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
          <InputLabel id="currency-label">Currency</InputLabel>
                <Select
                  labelId="currency-label"
                  id="currency-label"
                  value={currency}
                  label="Currency"
                  onChange={handleChange('currency')}
                >
                  <MenuItem value={39000.05}>BTC - 39000.05€</MenuItem>
                  <MenuItem value={1200}>ETH - 1200€</MenuItem>
                </Select>
        </FormControl>

        
        <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
        <InputLabel id="wallet-label">Wallet</InputLabel>
              <Select
                labelId="wallet-label"
                id="wallet-label"
                value={wallet}
                label="wallet"
                onChange={handleChange('wallet')}
              >

        {wallets.map(item => (
          <MenuItem value={item.id}>{item.name} - {item.currency}</MenuItem>
        ))}


              </Select>
        </FormControl>


        <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
          <TextField onChange={handleChange('amount')} id="filled-basic" type="number" label="Amount" variant="filled"  inputProps={{ inputMode: 'numeric', pattern: '[0-9]*' }}/>
        </FormControl>

     {/* <p>Price: {currency}, Wallet: {wallet}, Amount: {amount}</p> */}

     <h3>Wert: {(currency*amount).toFixed(1)} </h3>


        <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
          <Button variant="contained" onClick={kaufen} >kaufen</Button>
        </FormControl>





          </Paper>
        </Grid>
        <Grid item md={4}
        >
          <Paper className="main-grid" elevation={24}>
          <h1 className="main-grid-ueb">Wallets: {walletSum}€</h1>

        


        <Grid
        container
        spacing={0}
        direction="column"
        alignItems="center"
        justifyContent="center"
          >



              <List
                sx={{
                  width: '100%',
                  maxWidth: 360,
                  bgcolor: 'background.paper',
                }}
              >



            {wallets.map(item => (
                <ListItem>
                  <ListItemAvatar>
                    <Avatar>
                      <PriceCheckIcon />
                    </Avatar>
                  </ListItemAvatar>

              
                  <ListItemText primary={item.name} secondary={`Amount: ${item.amount}, Price: ${item.price}€`} />
                </ListItem>
                
                ))}

              
                          




              </List>
        </Grid>



          </Paper>
        </Grid>

        <Grid item md={2}>
        </Grid>


      </Grid>


    </Box>





    </div>

    
  );
}

export default App;
