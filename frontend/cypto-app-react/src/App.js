import logo from './logo.svg';
import './App.css';

import * as React from 'react';

import {Button, Grid, Box, Paper, AppBar, Toolbar,Typography, IconButton, InputLabel,
MenuItem, FormControl, Select, TextField, Stack, Chip, List, ListItem, ListItemText, ListItemIcon, Divider, Avatar, ListItemAvatar
} from '@mui/material';


import MenuIcon from '@mui/icons-material/Menu';
import PriceCheckIcon from '@mui/icons-material/PriceCheck';
import ImageIcon from '@mui/icons-material/Image';
import WorkIcon from '@mui/icons-material/Work';
import BeachAccessIcon from '@mui/icons-material/BeachAccess';
import React2, { useState, useEffect } from 'react';


function App() {

    const [currency, setCurrency] = React.useState();
    const [wallet, setWallet] = React.useState();
    const [amount, setAmount] = React.useState();

    const [data, setData] = useState(null);
  
    const handleChange = (tag) => (event) => {

      if(tag == 'currency') {
        setCurrency(event.target.value);
      } else if(tag == 'wallet') {
        setWallet(event.target.value);
      } else if(tag == 'amount') {
        setAmount(event.target.value);
      }

    };


  useEffect(() => {
    fetch(`http://localhost/cry/server/api/wallet/`)
      .then((response) => response.json())
      .then((actualData) => setData(actualData));
    
  }, []);


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
                <MenuItem value={1}></MenuItem>
                <MenuItem value={2}>ETH - Noglass Wallet</MenuItem>
              </Select>
        </FormControl>


        <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
          <TextField onChange={handleChange('amount')} id="filled-basic" type="number" label="Amount" variant="filled"  inputProps={{ inputMode: 'numeric', pattern: '[0-9]*' }}/>
        </FormControl>

     {/* <p>Price: {currency}, Wallet: {wallet}, Amount: {amount}</p> */}

     <h3>Wert: {(currency*amount).toFixed(1)} </h3>


        <FormControl variant="filled" style={{minWidth: 320}} className="main-select">
          <Button variant="contained">kaufen</Button>
        </FormControl>





          </Paper>
        </Grid>
        <Grid item md={4}
        >
          <Paper className="main-grid" elevation={24}>
          <h1 className="main-grid-ueb">Wallets: 3402€</h1>


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
                <ListItem>
                  <ListItemAvatar>
                    <Avatar>
                      <PriceCheckIcon />
                    </Avatar>
                  </ListItemAvatar>
                  <ListItemText primary="Bitcoins" secondary="0.05 BTC, 1752 €" />
                </ListItem>
                <Divider variant="inset" component="li" />
                <ListItem>
                  <ListItemAvatar>
                    <Avatar>
                      <PriceCheckIcon />
                    </Avatar>
                  </ListItemAvatar>
                  <ListItemText primary="Etherium" secondary="0.05 ETH, 1752 €" />
                </ListItem>
                <Divider variant="inset" component="li" />
                <ListItem>
                  <ListItemAvatar>
                    <Avatar>
                      <PriceCheckIcon />
                    </Avatar>
                  </ListItemAvatar>
                  <ListItemText primary="Monero" secondary="0.05 MONE, 1752 €" />
                </ListItem>
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
