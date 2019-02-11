<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 21:02
 */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Foglalások');

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <script src="https://unpkg.com/react@16/umd/react.development.js"></script>
            <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
            <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>

            <div id="root"></div>

            <script type="text/babel">
                class Timer extends React.Component {
                    constructor(props) {
                        super(props);

                        this.state = {
                            'color': props.color,
                            'sec': 0,
                            'minutes': 0,
                            'hours': 0
                        };
                    }

                    tick() {
                        this.setSecunds();
                    }

                    setSecunds() {
                        let sec = this.state.sec;

                        sec = sec + 1;

                        if (sec === 3) {
                            sec = 0;
                            this.setMinutes();
                        }

                        this.setState(
                            state => (
                                {
                                    sec: sec,

                                }
                            )
                        );
                    }

                    setMinutes(){
                        let minutes = this.state.minutes;

                        minutes = minutes + 1;

                        if (minutes === 3) {
                            minutes = 0;
                            this.setHours();
                        }

                        this.setState(
                            state => (
                                {
                                    minutes: minutes,
                                }
                            )
                        );
                    }

                    setHours() {
                        let hours = this.state.hours;

                        hours = hours + 1;

                        if (hours === 24) {
                            hours = 0;
                        }

                        this.setState(
                            state => (
                                {
                                    hours: hours,
                                }
                            )
                        );
                    }


                    componentDidMount() {
                        this.interval = setInterval(() => this.tick(), 1000);
                    }

                    componentWillUnmount() {
                        clearInterval(this.interval);
                    }

                    render() {
                        return (
                            <div style={{color: this.state.color}}>
                                {this.state.hours} : {this.state.minutes} : {this.state.sec} sec
                            </div>
                        );
                    }
                }

                ReactDOM.render(
                    <Timer color='red' />,
                    document.getElementById('root')
                );
            </script>
        </div>
    </div>
</div>

