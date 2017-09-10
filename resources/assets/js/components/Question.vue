<template lang="html">
<div class="col-md-12">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><b>{{ liveQuestion.body }}</b></h4></div>

            <div class="panel-body">
                <div v-if="liveQuestion.isMultiple" >
                    <!-- Multiple choice options -->
                    <multiple :liveQuestion='liveQuestion' v-if="!show"></multiple>    
                </div>
                <div v-else>
                    <!-- Short answer -->
                    <short :liveQuestion='liveQuestion' v-if="!show"></short>   
                </div>

                <ul class="list-group" style=" font-size: 1px;">
                    <div align="inline" :liveQuestion="liveQuestion" v-for="(hint, index) in liveQuestion.hint"><br>
                        
                        <li class="list-group-item" style="font-size: 14px; display: inline-block;">
                            <a  data-toggle="collapse" :data-target="addHash(index)" aria-expanded="false" aria-controls="collapseExample" @click.prevent="getHint(hint)">
                                <b>GET Hint {{index+1}}:</b>  {{hint.cost}} points
                            </a>
                            <div class="collapse" :id="index">
                                <div class="card card-block">
                                    {{hint.body}}
                                </div>
                            </div>
                        </li>
                    </div>
                </ul>
                <div v-show="show">
                        <h2> Correct Answers: </h2>
                        <div v-for="question in questions" class="list-group">
                            <h4 class="list-group-item-heading">{{question.body}}</h4>
                            <div v-if="question.isMultiple">
                                <div v-for="multiple in question.multiple">
                                    <div v-show="multiple.isCorrect">
                                        <p class="list-group-item-text">
                                            <li class="list-group-item">{{multiple.answer}}</li>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div v-else>    
                                <p class="list-group-item-text">
                                    <li class="list-group-item">{{question.short.correct_answer}}</li>
                                </p>
                            </div>    
                            
                        </div>
                    </div>

            </div>

        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading" :seconds="seconds"><h4> {{ seconds }} </h4></div>

            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item justify-content-between">
                        LEADERBOARD
                    </li>
                    <div :scores="scores" v-for="(score,index) in scores"> 
                        <li class="list-group-item justify-content-between">
                            {{index+1}}. {{score.student.first_name}} 
                            <span class="badge badge-default badge-pill">{{score.points}}</span>
                        </li>
                    </div>

                </ul>
            </div>
        </div>
    </div>
</div>
</template>



<script>
    export default {

        props: ['questions'],
        data() {
            return{
                id: 0,
                scores: [],
                quiz_id:this.questions[0].quiz_id,
                liveQuestion: [],
                seconds: 0,
                show:false,
            }
        },        
    

        methods: {
            getHint(hint){
                axios.post('/live/gethint',{
                    hint_id: hint.id,
                });
                
            },
            addHash(index){
                return '#'+index;
            },

            //Function that LOADs SCORES to leaderboard
            loadScores: function () {
                axios.get('/live/scores/',{
                    params: {
                        quiz_id: this.quiz_id
                    }}).then(function(response){
                    //console.log(response); // ex.: { user: 'Your User'}
                    this.scores = response.data.scores;
                }.bind(this));
            },
            //Function that loads live question
            loadQuestion: function () {
                axios.get('/live/question/',{
                    params: {
                        quiz_id: this.quiz_id
                    }}).then(function(response){
                    //If null then quiz ended//if not then update livequestion
                    if(response.data.liveQuestion == 'null'){
                        this.liveQuestion.body = 'Quiz ended';
                        this.seconds=response.data.seconds;
                        this.show = true;
                    }else{
                        this.liveQuestion = response.data.liveQuestion;
                        this.seconds = response.data.seconds;
                    }
                }.bind(this));
            }
        },
        //Functions that call loadScores every 2s and loadQuestion every 1s
        mounted: function () {
            this.loadScores();
            setInterval(function () {
                this.loadScores();
            }.bind(this), 2000); 

            this.loadQuestion();
            setInterval(function () {
                this.loadQuestion();
            }.bind(this), 1000); 
        },



    }
</script>

<style lang="css">
.message{
    color:green;
    font-family: "Times New Roman", Times, serif;
}
</style>