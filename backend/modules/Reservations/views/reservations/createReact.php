
<div id="root"></div>
<style>
    pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
    .string { color: green; }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: red; }
    .ticket{font-size: 15px; color: #367fa9;}
    .time{font-size: 15px; color: #367fa9;}
    .title{font-size: 25px; color: #367fa9;text-align:center;margin:50px 0px 30px 0px;font-family: georgia;}
    label{font-size: 18px; color: #367fa9;text-align:center}
    form{ margin:20px 0px;}

    .inputGroup {
    background-color: #fff;
    display: block;
    margin: 10px 0;
    position: relative;}

    .ticket{
        padding:30px 10px;
        border:solid 2px #f5f5f5;
        font-size:20px;
        font-family: gergia;
        text-align: center;
    }

    .ticket:nth-child(even){background-color: #f5f5f5;}

    .currencyLabel {
      padding: 12px 30px;
      width: 20%;
      display: block;
      border-radius: 30px;
      box-shadow: 0px 0px 15px 2px #e8e8e8;
      text-align: left;
      color: #3C454C;
      cursor: pointer;
      position: relative;
      z-index: 2;
      transition: color 200ms ease-in;
      overflow: hidden;}

    .currencyInput:checked ~ label {
      color: #fff;
      background-color: #2de692;
      border-color: #2de692; 
    }

    .currencyInput {
      width: 32px;
      height: 32px;
      order: 1;
      z-index: 2;
      position: absolute;
      right: 30px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      visibility: hidden;
    }

    

    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    outline: none;
    }
    .switch input {
    position: absolute;
    top: -99999px;
    left: -99999px;
    }
    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 34px;
    }
    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
    }
    input:checked + .slider {
    background-color: #2de692;
    }
    input:focus + .slider {
    box-shadow: 0 0 1px #2de692;
    }
    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    #rDebug{display:none;}

    select{
        padding: 13px 20px;
        border-radius: 20px;
        font-size:17px;
        width: 100%;
        border: none;
        box-shadow: 0px 0px 15px 2px #e8e8e8;
        margin-top:20px;
    }

    .pulse:hover, .pulse:focus{
        animation: pulse 1s;
        box-shadow:0 0 0 2em rgba(#fff,0);
    }

    @keyframes pulse {0% { box-shadow: 0 0 0 0 var(--hover); }}

    input[type="number"]{
        padding: 13px 20px;
        font-size:17px;
        text-align: center;
        border-radius: 20px;
        width: 100%;
        border: none;
        box-shadow: 0px 0px 15px 2px #e8e8e8;
        margin: 20px 0px 10px 0px
    }

    input[type="submit"]{
        background-color: #fff;
        color: #367fa9;
        margin: 40px 0px 0px 0px;
        padding: 15px 45px;
        font-size: 18px;
        width:100%;
        border: none;
        border-radius: 20px;
        box-shadow: 0px 0px 15px 2px #e8e8e8;
    }

    input[type=number]::-webkit-inner-spin-button{
        transition: opacity 0.15s;opacity: 0.5;
    }

    @media only screen and (max-width:500px){
        form{
            text-align:center;
        }

        

        .currencyLabel{width:100%;}

        input[type="number"]{
            text-align:center;
            font-size:20px;
        }
        select{
            text-align-last:center;
        }
    }

    .select-css {
        display: block;
        max-width: 100%;
        box-sizing: border-box;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
        background-repeat: no-repeat, repeat;
        background-position: right .7em top 50%, 0 0;
        background-size: .65em auto, 100%;
    }
    .select-css::-ms-expand {
        display: none;
    }



    .option-group {
        width: 90%;
        max-width: 400px;
        height: 150px;
        position: relative;
        overflow: hidden;
        border-radius: 0.25em;
        font-size: 4rem;
        margin: 0.2em auto;
    //  will-change: transform;
        transform: translateZ(0);
    }

    .option-container {
        display: flex;
        justify-content: center;
        align-items: stretch;
        width: 120%;
        height: 100%;
        margin: 0 -10%;
    }

    .option {
        overflow: hidden;
        flex: 1;
        color:white;
        display: block;
        padding: 0.5em;
        background: #367fa9;
        position: relative;
        margin: 0em;
        margin-right: 0.2em;
    &:last-child { margin-right: 0; }

    border-radius: 0.25em;

    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
    flex-direction: column;

    cursor: pointer;

    opacity: 0.5;
    transition-duration: 0.8s, 0.6s;
    transition-property: transform, opacity;
    transition-timing-function: cubic-bezier(.98,0,.22,.98), linear;
    will-change: transform, opacity;
    }

    .option__indicator {
        display: block;
        transform-origin: left bottom;
        transition: inherit;
        will-change: transform;
        position: absolute;
        top: 0.5em;
        right: 0.5em;
        left: 0.5em;

    &:before,
    &:after {
         content: '';
         display: block;
         border: solid 2px #64D6EE;
         border-radius: 50%;
         width: 0.25em;
         height: 0.25em;
         position: absolute;
         top: 0;
         right: 0;
     }

    &:after {
         background: #64D6EE;
         transform: scale(0);
         transition: inherit;
         will-change: transform;
     }
    }

    .option-input {
        position: absolute;
        top: 0;
        z-index: -1;
        visibility: hidden;
    }

    .option__label {
        display: block;
        width: 100%;
        text-transform: uppercase;
        font-size: 1.5em;
        font-weight: bold;

        transform-origin: left bottom;
        transform: translateX(20%) scale(0.7);
        transition: inherit;
        will-change: transform;

    &:after {
         content: '';
         display: block;
         border: solid 2px #64D6EE;
         width: 100%;
         transform-origin: 0 0;
         transform: scaleX(0.2);
         transition: inherit;
         will-change: transform;
     }
    }

    .option:last-child {
    .option__label { transform: translateX(0%) scale(0.7); }
    .option__indicator { transform: translateX(-20%); }
    }

    .option-input:checked ~ .option {
        transform: translateX(-20%) translateX(0.2em);
    .option__indicator { transform: translateX(0%); }
    .option__label { transform: translateX(40%) scale(0.7); }
    }

    .option-input:first-child:checked ~ .option {
        transform: translateX(20%) translateX(-0.2em);
    .option__indicator { transform: translateX(-40%); }
    .option__label { transform: translateX(0%) scale(0.7); }
    }

    .option-input:nth-child(1):checked ~ .option:nth-of-type(1),
    .option-input:nth-child(2):checked ~ .option:nth-of-type(2) {
        opacity: 1;
    .option__indicator { transform: translateX(0); &::after { transform: scale(1); } }
    .option__label,
    .option__label::after { transform: scale(1); }
    }


</style>

<script type="text/babel">
    class ReservationsCreate extends React.Component {
        constructor(props) {
            super(props);
            const input = <?php echo $data; ?>;

            this.state = {
                data: input,
                ido: 1,
                totalPrice: 0,
                Price: [],
                paid: 'unpaid',
                display:'none',
                isChecked: false,
                createReservation:[
                    {idReservation: 0},
                    {priceReservation: 0},
                    {timeReservation: 0},
                    {ticketReservation: 0},
                ],
            };

            this.handleChange = this.handleChange.bind(this);
            this.handlePick = this.handlePick.bind(this);
            this.handleSubmit = this.handleSubmit.bind(this);
            this.totalCounter = this.totalCounter.bind(this);
            this.handleCurrency = this.handleCurrency.bind(this);
        }



        syntaxHighlight(json) {
            if (typeof json != 'string') {
                json = JSON.stringify(json, undefined, 2);
            }
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        //product pick -> kiválasztott product ideje es jegytipusai
        handleChange(event) {
            this.setState({ ido: event.currentTarget.value })
            event.preventDefault();
            console.log(2)
        }

        handleSubmit(event) {
            event.preventDefault();
        }

          
        handlePick = (ev) => {
            this.setState({
                isChecked: ev.target.checked
            })

            if(this.state.isChecked === false){
                this.setState({
                    display: "block"
                })
            }
            else(
                this.setState({
                    display: "none"
                })
            )          
        }

        handleCurrency(event){
            this.setState({
                paid: event.currentTarget.value
            })

            console.log(this.state.paid)
        }
       
        totalCounter(e) {
            const { Price } = this.state;
            const { totalCounter } = this.state;
            this.setState({ Price: e.currentTarget.value })
            this.setState({ totalCounter: e.currentTarget.value })          
        }

        





        render() {
            const { data, ido, Price} = this.state;
            const dataText = this.syntaxHighlight(data);


            //program választó
            const RenderProgram = (props) => {
                return(
                    <div>
                            <i className="fa fa-user-happy"></i>
                            <div className="title">
                                SELECT PROGRAM</div>
                            <div className="option-group">
                                <div className="option-container" >
                                    <input value="1" onChange={this.handleChange} checked={this.state.ido === '1'}  className="option-input" id="option-1" type="radio" name="options" />
                                    <input value="3" onChange={this.handleChange} checked={this.state.ido === '3'}  className="option-input" id="option-2" type="radio" name="options" />
                                    <label className="option" htmlFor="option-1">
                                        <span className="option__indicator"></span>
                                        <span className="option__label">
                                        Sightseeng
                                    </span>
                                    </label>
                                    <label className="option" htmlFor="option-2">
                                        <span className="option__indicator"></span>
                                        <span className="option__label">
                                        Pizza
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div className="option-group">
                                <div className="option-container">
                                    <input onChange={this.handleChange} checked={this.state.ido === '8'}  value="8" className="option-input" id="option-3" type="radio" name="options" />
                                    <input onChange={this.handleChange} checked={this.state.ido === '9'}  value="9" className="option-input" id="option-4" type="radio" name="options" />
                                    <label className="option" htmlFor="option-3">
                                        <span className="option__indicator"></span>
                                        <span className="option__label">
                                            Folklore
                                        </span>
                                    </label>
                                    <label className="option" htmlFor="option-4">
                                        <span className="option__indicator"></span>
                                        <span className="option__label">
                                            Piano
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <br />
                        </div>
                )
            }

            //timesok létrehozása
            const RenderTime = (props) => {
                const { product } = props;
                return(
                    data[product].times.map(function(time, idx) {
                        return <option className="time" key={idx}>
                            {time.name}
                        </option>;})
                )
            }


            //ticket típusok létrehozása
            const RenderTicket = (props) => {
                const { product } = props;
                return(
                    data[product].prices.map((ticket, idx) => {
                        return <div className="ticket" key={idx}>
                            {ticket.name}<br />
                            <input type="number" id={ticket.id} placeholder="0"className="mod" value={Price[idx]} name={`price_${ticket.id}`} onChange={this.totalCounter} />
                            
                            <br />
                        </div>;})
                )
            }

            //kapcsoló renderelés
            const RenderToggle = (props)=>{
                return (
                    <div>
                        <div className="title">PAID OR NOT</div>
                        <label className="switch">
                            <input type="checkbox" checked={this.state.isChecked} onChange={this.handlePick} />
                            <div className="slider"></div>
                        </label>
                    </div>
                );
            }

            //currency renderelés
            const RenderCurrency = ()=>{
                return (
                    <div style={{display:this.state.display, margin: '20px 0px'}}>
                    <hr />              
                        <div className="inputGroup">
                            <input className="currencyInput" onChange={this.handleCurrency} id="option1" value="EURO" name="option" type="radio"/>
                            <label className="currencyLabel" htmlFor="option1">€ - EURO</label>
                        </div>
                        
                        <div className="inputGroup">
                            <input className="currencyInput" onChange={this.handleCurrency} id="option2" value="FORINT" name="option" type="radio"/>
                            <label className="currencyLabel" htmlFor="option2">Ft - FORINT</label>
                        </div>
                    </div>  
                );
            }






            const RenderTitle = (props) => {
                return(
                    <form onSubmit={this.handleSubmit}>

                        <RenderProgram />
                        <br />
                        <div>
                        <div className="title">SELECT TIME</div>
                            <br />
                            <select className="select-css" >
                                <RenderTime product={ido} />
                            </select>
                        </div>
                        <div className="title">SELECT TICKET</div>
                        <div>
                            <RenderTicket product={ido} />
                        </div>
                        <div>                          
                            <RenderToggle />
                        </div>   
                        <div>
                            <RenderCurrency />
                        </div>            
                        
                        <input className="pulse" type="submit" value="Submit »" />
                        
                    </form>
                )
            }




            return (
                <div className="container">
                    <div id="rDebug" dangerouslySetInnerHTML={{ __html: dataText }} />
                    <RenderTitle />
                </div>

            );
        }
    }

    ReactDOM.render(
        <ReservationsCreate />,
        document.getElementById('root')
    );
</script>


