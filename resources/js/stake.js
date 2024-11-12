import {createApp, ref} from 'vue/dist/vue.esm-bundler'
import {init, useOnboard} from '@web3-onboard/vue'
import injectedModule from '@web3-onboard/injected-wallets'
import {watch} from "vue";
import axios from "axios";
import Swal from 'sweetalert2'
import {ethers} from "ethers";

const injected = injectedModule()

const wallets = [injected]

// let web3 = new Web3(window.ethereum);
let contractAddress = import.meta.env.VITE_USDT_CONTRACT_ADDRESS
let companyDepositWalletAddress = import.meta.env.VITE_USDT_DEPOSIT_WALLET_ADDRESS
let contractAbi = ''

if (import.meta.env.VITE_APP_ENV === 'production') {
    contractAbi = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}]'
} else {
    contractAbi = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}]'
}

let chainId;
let rpcUrl;
let chainName;
let contractDecimal;
let token;

if (import.meta.env.VITE_APP_ENV === 'production') {
    chainId = '0x38'
    rpcUrl = 'https://bsc-dataseed.binance.org'
    chainName = 'Binance - Mainnet'
    contractDecimal = 18
    token = 'BNB'
} else {
    chainId = '0x61'
    rpcUrl = 'https://data-seed-prebsc-1-s1.binance.org:8545'
    chainName = 'Binance - Testnet'
    contractDecimal = 18
    token = 'BNB'
}

const onboardInit = init({
    wallets: wallets,
    connect: {
        autoConnectLastWallet: true
    },
    appMetadata: {
        name: "Pologain",
        icon: "<svg><svg/>",
        description: "Connect with your wallet",
        recommendedInjectedWallets: [
            {name: "MetaMask", url: "https://metamask.io"},
            {name: "TrustWallet", url: "https://trustwallet.com"},
        ],
    },
    chains: [
        {
            id: chainId,
            token: token,
            label: chainName,
            rpcUrl: rpcUrl
        },
    ],
});

createApp({
    setup() {
        const {connectWallet, connectedChain, connectedWallet, setChain, getChain, connectingWallet} = useOnboard()

        const connect = async () => connectWallet()
        const setProperChain = async () => setChain({wallet: connectedWallet.value.label, chainId: chainId})

        const walletAddress = ref('')
        const amount = ref('')
        const stakeCoinValidationError = ref('')
        const isStakeCoinValidationFail = ref(false)
        const web3WalletBalance = ref(0)

        watch(connectedWallet, async (newWallet, oldWallet) => {
            if (newWallet !== null) {
                walletAddress.value = newWallet.accounts[0]['address']

                if (connectedChain.value.id === chainId) {
                    await getWeb3WalletBalance(walletAddress.value)
                }
            } else {
                walletAddress.value = ''
            }
        });

        const stakeCoinValidation = async () => {
            preLoaderOn();

            await getWeb3WalletBalance(walletAddress.value)

            if (parseFloat(web3WalletBalance.value) < parseFloat(amount.value)) {
                preLoaderOff()

                isStakeCoinValidationFail.value = true
                stakeCoinValidationError.value = ''

                await Swal.fire({
                    title: "Oops...",
                    text: 'You do not have enough wallet balance for transaction',
                    icon: "error"
                });

                return '';
            }

            await axios.post('/user/stake/store-validation', {
                'walletAddress': walletAddress.value,
                'amount': amount.value,
            })
                .then(function (response) {
                    if (response.data.status === true) {
                        isStakeCoinValidationFail.value = false
                    } else {
                        isStakeCoinValidationFail.value = true

                        Swal.fire({
                            title: "Oops...",
                            text: response.data.message,
                            icon: "error"
                        });
                    }

                    preLoaderOff()
                })
                .catch(function (error) {
                    if (error.response.status === 422) {
                        stakeCoinValidationError.value = ''
                        stakeCoinValidationError.value = error.response.data

                        isStakeCoinValidationFail.value = true

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.response.data,
                        })

                        preLoaderOff()
                    }
                })
        }

        const getWeb3WalletBalance = async (walletAddress) => {
            try {
                const provider = await new ethers.providers.Web3Provider(connectedWallet.value.provider);
                const signer = provider.getSigner(walletAddress);

                const tokenInstance = new ethers.Contract(
                    contractAddress,
                    JSON.parse(contractAbi),
                    signer
                );

                const balance = await tokenInstance
                    .balanceOf(walletAddress);

                web3WalletBalance.value = balance.toBigInt();
            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message.replace(".", ""),
                });
            }
        }
        const stakeCoin = async (e) => {
            e.preventDefault()

            await stakeCoinValidation()

            if (!isStakeCoinValidationFail.value) {

                let amountBN = ethers.utils.parseUnits(BigInt(amount.value * (10 ** contractDecimal)).toString(), "wei");

                if (BigInt(amountBN.toString()) > web3WalletBalance.value) {
                    Swal.fire('Oops!!!', 'Not enough USDT balance to transfer!', 'error')

                    return;
                }
                preLoaderOn();

                const provider = await new ethers.providers.Web3Provider(connectedWallet.value.provider);
                const signer = provider.getSigner(walletAddress.value);

                const tokenInst = new ethers.Contract(
                    contractAddress,
                    JSON.parse(contractAbi),
                    signer
                );

                try {
                    let transactionData = {
                        from: walletAddress.value,
                    };

                    if (connectedWallet.value.label === 'MetaMask') {
                        transactionData.gasPrice = await provider.getGasPrice();
                    } else {
                        const gasLimit = await tokenInst.estimateGas.transfer(companyDepositWalletAddress, amountBN);
                        const feeData = await provider.getFeeData();

                        transactionData.gasLimit = gasLimit;
                        transactionData.maxFeePerGas = feeData.maxFeePerGas;
                        transactionData.maxPriorityFeePerGas = feeData.maxPriorityFeePerGas;
                    }

                    const transaction = await tokenInst.transfer(companyDepositWalletAddress, amountBN, transactionData);

                    await axios.post('/user/stake/store', {
                        'walletAddress': walletAddress.value,
                        'amount': amount.value,
                        'transactionHash': transaction.hash,
                    })
                        .then(function (response) {
                            if (response.data.status === true) {
                                location.href = '/user/stake'
                            } else {
                                Swal.fire({
                                    title: "Oops...",
                                    text: response.data.message,
                                    icon: "error"
                                });
                            }
                            preLoaderOff()
                        })
                        .catch(function (error) {
                            if (error.response.status === 422) {
                                stakeCoinValidationError.value = ''
                                stakeCoinValidationError.value = error.response.data
                                isStakeCoinValidationFail.value = true
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.response.data,
                            })

                            preLoaderOff()
                        })
                } catch (error) {
                    preLoaderOff()

                    if (error.data) {
                        let message = error.data.message.charAt(0).toUpperCase() + error.data.message.slice(1);
                        message = message.replace(/\(0\)/g, '')

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })

                        return ''
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.reason[0].toUpperCase() + error.reason.slice(1),
                    })

                }
            }
        }


        const preLoaderOn = () => {
            $("#preloader").fadeIn();
            $('#preloader > #status').fadeIn();
        }

        const preLoaderOff = () => {
            $("#preloader").fadeOut();
            $('#preloader > #status').fadeOut();
        }
        const bigIntToEther = (value) => {
            return ethers.utils.formatUnits(value, contractDecimal)
        }

        return {
            connect,
            setProperChain,
            connectWallet,
            connectedWallet,
            connectedChain,
            getChain,
            connectingWallet,
            chainId,
            rpcUrl,
            chainName,
            walletAddress,
            amount,
            isStakeCoinValidationFail,
            stakeCoin,
            stakeCoinValidationError,
            getWeb3WalletBalance,
            web3WalletBalance,
            bigIntToEther
        }
    },
}).mount('#app')
