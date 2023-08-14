const { createBot, createProvider, createFlow, addKeyword, EVENTS } = require('@bot-whatsapp/bot')
const BaileysProvider = require('@bot-whatsapp/provider/baileys')
const MySQLAdapter = require('@bot-whatsapp/database/mysql')

const express = require('express');
const axios = require('axios');
const cors = require('cors')

require('dotenv').config({path: '../../.env'})
const MYSQL_DB_HOST = process.env.DB_HOST
const MYSQL_DB_USER = process.env.DB_USERNAME
const MYSQL_DB_PASSWORD = process.env.DB_PASSWORD
const MYSQL_DB_NAME = process.env.DB_DATABASE
const MYSQL_DB_PORT = process.env.DB_PORT




//percyalvarez2023----------------------------------------------------------
const flujoOpcion01 = addKeyword(['1', '2', '3', '4'])
    .addAnswer(
        'Consultando a la base de datos, espere un momento por favor ⏱️',
        null,
        async (ctx, {flowDynamic}) => {
            var misession = await axios.post(process.env.APP_URL+'/api/setting', {
                username: process.env.cliente01
            })
            console.log(ctx)
            const {from, body} = ctx
            switch (body) {
                case '1':
                    return await flowDynamic([{ body: misession.data.milocation[0].custom_field1 }])
                    break;
                case '2':
                    return await flowDynamic([{ body: misession.data.milocation[0].custom_field2 }])
                    break;
                case '3':
                    return await flowDynamic([{ body: misession.data.milocation[0].custom_field3 }])
                    break;
                case '4':
                    return await flowDynamic([{ body: misession.data.milocation[0].custom_field4 }])
                    break;            
                default:
                    break;
            }
        },
        []
        )

const flujoWelcome01 = addKeyword(EVENTS.WELCOME)
    .addAnswer(
        '*Hola 🙌, soy un bot🤖, te puedo ayudar con las siguientes opciones:*',
        null,
        async (ctx, {flowDynamic}) => {
            var misession = await axios.post(process.env.APP_URL+'/api/setting', {
                username: process.env.cliente01
            })
            console.log(ctx)
            var milabel = JSON.parse(misession.data.minegocio.business.custom_labels)
            var misms = '1.- '+milabel.location.custom_field_1
            misms +=  '\n2.- '+milabel.location.custom_field_2
            misms +=  '\n3.- '+milabel.location.custom_field_3
            misms +=  '\n4.- '+milabel.location.custom_field_4
            misms +=  '\n\n*Envia un numero para ingresar al menu (1, 2, 3 ..)*'            
            return await flowDynamic([{ body: misms }])
        },
        [flujoOpcion01]
        )
const adapterProvider1 = createProvider(BaileysProvider, {
    name: process.env.cliente01
})
const percyalvarez2023 = async () => {
    const adapterDB = new MySQLAdapter({
        host: MYSQL_DB_HOST,
        user: MYSQL_DB_USER,
        database: MYSQL_DB_NAME,
        password: MYSQL_DB_PASSWORD,
        port: MYSQL_DB_PORT,
    })
    const adapterFlow = createFlow([flujoWelcome01, flujoOpcion01])
    createBot({
        flow: adapterFlow,
        provider: adapterProvider1,
        database: adapterDB,
    })
    //leads
    adapterProvider1.on('message', async (ctx) => {
        const {from, body} = ctx
        var midata = await axios.post(process.env.APP_URL+'/api/leads', {
            phone: from,
            message: body,
            session: process.env.cliente01
        })        
    })

    
}
percyalvarez2023()


// api--------------------------------------------------------------------
const app = express();
app.use(cors())
app.use(express.json())

app.listen(process.env.CB_PORT, () => {
    console.log('CHATBOT ESTA LISTO EN EL PUERTO: '+process.env.CB_PORT);
});

// rutas---------------------------------------------------------------
//home
app.get('/', async (req, res) => {
    res.send('CHATBOT ESTA LISTO EN EL PUERTO:'+process.env.CB_PORT);
});


//percyalvarez2023
app.post('/percyalvarez2023', async (req, res) => {
    switch (req.body.type) {
        case "group_info":
            try {
                const response = await adapterProvider1.vendor.groupGetInviteInfo(req.body.phone)
                res.send(response);
            } catch (error) {
                res.send(error)
            }
            break;
        case "message_text":
            try {
                adapterProvider1.vendor.sendMessage(req.body.phone+'@s.whatsapp.net', { text: req.body.message })
                res.send('message_text')
            } catch (error) {
                res.send(error)
            } 
            break
        case "message_image":
            try {
                adapterProvider1.vendor.sendMessage(req.body.phone+'@s.whatsapp.net', { 
                    image: {url: req.body.multimedia},
                    caption: req.body.message,
                    gifPlayback: true
                })
                res.send('message_image')
            } catch (error) {
                res.send(error)
            } 
            break
        case "message_group_text":
            try {
                adapterProvider1.vendor.sendMessage(req.body.phone+'@g.us', { text: req.body.message })
                res.send('message_group_text')
            } catch (error) {
                res.send(error)
            } 
        case "message_group_image":
                try {
                    adapterProvider1.vendor.sendMessage(req.body.phone+'@g.us', { 
                        image: {url: req.body.multimedia},
                        caption: req.body.message,
                        gifPlayback: true
                    })
                    res.send('message_group_image')
                } catch (error) {
                    res.send(error)
                } 
            break
        default:
            break;
    }
    // res.send("percyalvarez2023")
});