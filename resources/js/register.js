import {createApp, ref} from 'vue/dist/vue.esm-bundler'
import {init, useOnboard} from '@web3-onboard/vue'
import injectedModule from '@web3-onboard/injected-wallets'
import {onMounted, watch} from "vue";
import axios from "axios";
import Swal from 'sweetalert2'

const injected = injectedModule()

const wallets = [injected]

let chainId;
let rpcUrl;
let chainName;
let token;

if (import.meta.env.VITE_APP_ENV === 'production') {
    // chainId = '0x89'
    // rpcUrl = 'https://polygon-rpc.com'
    // chainName = 'Polygon - Mainnet'

    chainId = '0x38'
    rpcUrl = 'https://bsc-dataseed.binance.org'
    chainName = 'Binance - Mainnet'
    token = 'BNB'
} else {
    chainId = '0x61'
    rpcUrl = 'https://data-seed-prebsc-1-s1.binance.org:8545'
    chainName = 'Binance - Testnet'
}

const register = init({
    wallets: wallets,
    connect: {
        autoConnectLastWallet: true
    },
    appMetadata: {
        name: "PoloGain",
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


        const setBinanceChain = async () => setChain({wallet: connectedWallet.value.label, chainId: chainId})
        const walletAddress = ref('')
        const side = ref('')
        const isSideDisable = ref(false)
        const referralWalletAddress = ref('');
        const registerValidationError = ref('')

        const isUserRegistered = ref(false)

        watch(connectedWallet, async (newWallet, oldWallet) => {
            if (newWallet !== null) {
                walletAddress.value = newWallet.accounts[0]['address']

                await axios.get('check-wallet-address/' + walletAddress.value)
                    .then(function (response) {
                        if (response.data.isBlocked === true) {
                            Swal.fire({
                                title: "Oops...",
                                text: response.data.message,
                                icon: "error",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: "Go to dashboard",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    location.href = '/';
                                }
                            });

                            return ''
                        }

                        if (response.data.status === true) {
                            isUserRegistered.value = true
                            location.href = 'dashboard'
                        } else {
                            isUserRegistered.value = false
                        }
                    })

            } else {
                walletAddress.value = ''
            }
        });

        const register = async (e) => {
            // e.preventDefault()

            if (side.value === "") {
                sideError.value = 'Side is required'
                registerValidationError.value = ''

            } else {
                sideError.value = ''
            }

            if (termsCheckbox.value === false) {
                termsError.value = 'Terms & Conditions is required'
                registerValidationError.value = ''

            } else {
                termsError.value = ''
            }

            if (termsError.value !== '' || sideError.value !== '') {
                return '';
            }

            await axios.post('register', {
                'walletAddress': walletAddress.value,
                'referralWalletAddress': referralWalletAddress.value,
                'side': side.value,
            })
                .then(function (response) {
                    if (response.data.status === true) {
                        location.href = 'dashboard'
                    }

                    if (response.data.status === false) {
                        Swal.fire({
                            title: "Oops...",
                            text: response.data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(function (error) {
                    if (error.response.status === 422) {
                        registerValidationError.value = ''
                        registerValidationError.value = error.response.data
                    }
                })
        }

        const getReferralWalletAddressFromUrl = async () => {
            const queryString = window.location.search;

            const urlParams = new URLSearchParams(queryString);

            referralWalletAddress.value = urlParams.get('referralWalletAddress')
            side.value = urlParams.get('side')

            if(side.value){
                isSideDisable.value = true
            }

            console.log(referralWalletAddress.value);
            if (referralWalletAddress.value === null) {
                await axios.get('get-referral-wallet')
                    .then(function (response) {
                        if (response.data.status === true) {
                            referralWalletAddress.value = response.data.referralWalletAddress
                        }
                    })
            }
        }

        onMounted(async () => {
            await getReferralWalletAddressFromUrl()
        })

        const termsCheckbox = ref(false)
        const termsError = ref('')
        const sideError = ref('')

        return {
            connect,
            setBinanceChain,
            connectWallet,
            connectedWallet,
            connectedChain,
            getChain,
            connectingWallet,
            chainId,
            rpcUrl,
            chainName,
            walletAddress,
            referralWalletAddress,
            side,
            isUserRegistered,
            register,
            registerValidationError,
            termsCheckbox,
            termsError,
            sideError,
            getReferralWalletAddressFromUrl,
            isSideDisable
        }
    },
}).mount('#app')
