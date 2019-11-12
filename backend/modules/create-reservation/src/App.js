import React from 'react';
import './App.css';
import './styles.scss';

class App extends React.Component {
    constructor(props) {
        super(props);
        const input = props.data;

        this.state = {
            data: input,
            ido: 0,
            totalPrice: 0,
            Price: [],
            createReservation: [
                {idReservation: 0},
                {priceReservation: 0},
                {timeReservation: 0},
                {ticketReservation: 0},
            ],
            staticUrl: props.staticUrl
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.totalCounter = this.totalCounter.bind(this);
    }


    static syntaxHighlight(json) {
        if (typeof json !== 'string') {
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
            return '<span class="' + cls + '"> ' + match + '</span>';
        });
    }

    //product pick -> kiválasztott product ideje es jegytipusai
    handleChange(event) {
        event.preventDefault();
        this.setState({ido: event.currentTarget.value});
    }

    handleSubmit(event) {
        console.log('your pick: ' + this.state.ido);
        event.preventDefault();
    }


    totalCounter(e) {
        const {Price} = this.state;
        const {totalCounter} = this.state;

        console.log(`onChange fired with value: '${e.currentTarget.value}'`);

        this.setState({Price: e.currentTarget.value});
        console.log(Price);

        this.setState({totalCounter: e.currentTarget.value});
        console.log(totalCounter);
    }


    render() {
        const {data, ido, Price} = this.state;
        const dataText = App.syntaxHighlight(data);

        //timesok létrehozása
        const RenderTime = (props) => {
            const {product} = props;

            return (
                data[product].times.map(function (time, idx) {
                    return (
                        <option className="time" key={idx}>
                            {time.name}
                        </option>
                    );
                })
            )
        };


        //ticket típusok létrehozása
        const RenderTicket = (props) => {
            const {product} = props;
            console.log('renderTicket', props);
            return (
                data[product].prices.map((ticket, idx) => {
                    return <div className="ticket" key={idx}>
                        {ticket.name}<br/>
                        <input type="number" id={ticket.id} placeholder="0" className="mod" value={Price[idx]}
                               name={`price_${ticket.id}`} onChange={this.totalCounter}/>
                        <hr/>
                        <br/>
                    </div>;
                })
            )
        }


        const RenderTitle = (props) => {
            // console.log('renderTitle', props);

            return (
                <form onSubmit={this.handleSubmit}>

                    <div>
                        <i className="fa fa-user-happy"></i>
                        <label>
                            Select program:
                        </label>
                        <div className="option-group">
                            <div className="option-container">
                                <input value="1" onChange={this.handleChange} className="option-input" id="option-1"
                                       type="radio" name="options"/>
                                <input value="3" onChange={this.handleChange} className="option-input" id="option-2"
                                       type="radio" name="options"/>
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
                                <input onChange={this.handleChange} value="8" className="option-input" id="option-3"
                                       type="radio" name="options"/>
                                <input onChange={this.handleChange} value="9" className="option-input" id="option-4"
                                       type="radio" name="options"/>
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
                        <br/>
                        <hr/>
                    </div>
                    <br/>
                    <div>
                        <label>
                            Select time:
                        </label>
                        <br/>
                        <select className="select-css">
                            <RenderTime product={ido}/>
                        </select>
                        <hr/>
                        <br/>
                        <div>
                            <RenderTicket product={ido}/>
                        </div>
                    </div>


                    <div>
                        <input className="pulse" type="submit" value="Submit »"/>
                    </div>
                </form>
            )
        }


        return (
            <div className="container">
                <div id="rDebug" dangerouslySetInnerHTML={{__html: dataText}}/>
                <RenderTitle/>
            </div>

        );
    }
}

export default App;
