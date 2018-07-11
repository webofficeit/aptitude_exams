<template>
    <div class="">

        <!--acceptedExamInstructions- {{acceptedExamInstructions}}-->
        <div v-if='!acceptedExamInstructions' class="row">

            <div class="col-md-9">


                <div class="an-single-component with-shadow">
                    <div class="an-component-header" style="background: #BCE8F5;">
                        <h6>Instructions</h6>
                    </div>

                    <div class="an-component-body">
                        <div class="an-helper-block">
                            <!--<h4></h4>-->

                            <div v-html="instructions">

                            </div>

                            <div>


                                <div class="pull-right">
                                    <button class="an-btn an-btn-success" v-on:click="checkIfExamInstructionsAccepted()">Next</button>
                                </div>

                            </div>

                        </div>
                    </div> <!-- end .AN-COMPONENT-BODY -->
                </div>



            </div>


            <div class="col-md-3" style="background: #FFFFFF; border-left:1px solid #000; position:fixed; top:59px;right:0; height:1200px">

                <div class="col-md-offset-3">

                    <img src="/assets/img/NewCandidateImage.jpg" />

                    <div class="col-md-12 m20top">
                        {{usr && usr.name}}
                    </div>

                </div>

            </div>



        </div>

        <div class="row" v-if="finalResult">
            <div class="col-md-8 col-md-offset-2" v-html="finalResult"></div>
        </div>

        <div v-if='acceptedExamInstructions && !acceptedImportantInstructions' class="row">

            <div class="col-md-9">

                <div class="an-single-component with-shadow">
                    <div class="an-component-header" style="background: #BCE8F5;">
                        <h6>Instructions</h6>
                    </div>

                    <div class="an-component-body">
                        <div class="an-helper-block">

                            <div>
                                This is a Mock test. Question Paper displayed is for practice purpose only. Under no circumstances should this be presumed as a sample paper.
                            </div>


                            <div>

                                <table class="table" style="margin-top: 20px; border: 1px solid #d3d3d3">

                                    <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Questions</th>
                                        <th>Marks</th>
                                        <th>Duration</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <tr v-for="section in getSectionsWithQuestions">
                                        <td>
                                            {{ section.section_name }}
                                        </td>

                                        <td>{{ questionsInThisSection(section).length }}</td>

                                        <td>{{ exam.marks_per_question * +questionsInThisSection(section).length }}</td>

                                        <td>
                                            <span v-if="section.time">
                                                {{  section.time }} Minutes
                                            </span>
                                            <span v-else>
                                                _
                                            </span>
                                        </td>
                                    </tr>


                                    <tr>

                                        <td>Total</td>

                                        <td> {{ totalQuestionsOfExam }}
                                        </td>

                                        <td>
                                            {{ totalMarksOfExam }}
                                        </td>

                                        <td>
                                            {{  totalDurationOfExam> 60 ? Math.floor(totalDurationOfExam/60)+' Hour '+(totalDurationOfExam%60) +' Minutes' :  totalDurationOfExam+' Minutes' }}
                                        </td>

                                    </tr>

                                    </tbody>

                                </table>

                            </div>



                            <div>
                            <span class="an-custom-checkbox blocked">
                              <input type="checkbox" id="acceptImportantInstructions" v-model="acceptImportantInstructions" >
                              <label for="acceptImportantInstructions">I have read and understood the instructions. All computer hardware allotted to me are in proper working condition. I declare  that I am not in possession of / not wearing / not  carrying any prohibited gadget like mobile phone, bluetooth  devices  etc. /any prohibited material with me into the Examination Hall.I agree that in case of not adhering to the instructions, I shall be liable to be debarred from this Test and/or to disciplinary action, which may include ban from future Tests / Examinations</label>
                            </span>


                                <div class="col-sm-6">
                                    <button class="an-btn an-btn-primary" v-on:click="goBackToExamInstructions()">
                                        <i class="fa fa-chevron-left"></i> Previous
                                    </button>
                                </div>


                                <div class="col-sm-6">
                                    <button class="an-btn an-btn-success" v-on:click="checkIfImportantInstructionsAccepted()">I am ready to Begin</button>
                                </div>

                            </div>

                        </div>
                    </div> <!-- end .AN-COMPONENT-BODY -->
                </div>



            </div>


            <div class="col-md-3" style="background: #FFFFFF; border-left:1px solid #000; position:fixed; top:59px;right:0; height:1200px">

                <div class="col-md-offset-3">

                    <img src="/assets/img/NewCandidateImage.jpg" />

                    <div class="col-md-12 m20top">
                        {{usr && usr.name}}
                    </div>

                </div>

            </div>


        </div>



        <!--<div v-if='acceptedExamInstructions'>-->
        <div v-if='acceptedExamInstructions && acceptedImportantInstructions' v-show="! ifExamIsCompleted">
            <div class="an-inbox-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content" >

                            <div role="tabpanel" class="tab-pane fade active in" id="sent">

                                <div class="an-single-component with-shadow">

                                    <div class="an-component-header">

                                        <div class="col-md-12">

                                            <div class="col-sm-3" v-for="(section, index) in getSectionsWithQuestions" :style="{backgroundColor: section.active ? colors() : '#f3f3f3' }" >
                                                {{ section.section_instructions }}
                                                <h6 v-if="!exam.duration">SECTION: {{section.section_name}}</h6>

                                                <a href="#" @click.prevent="updateSection(index)" v-if="exam.duration" >
                                                    <h6>SECTION: {{section.section_name}}</h6>
                                                </a>
                                            </div>

                                        </div>

                                    </div>



                                    <div class="an-component-body an-inbox-body">

                                        <div class="row"  >
                                            <div class="col-md-9">
                                                <div class="an-inbox-message-details">

                                                    <div class="title">

                                                        <h5><strong>Question No. {{question.number }} </strong></h5>
                                                    </div>

                                                    <div class="message-body m">

                                                        <div v-if="question.instructions">
                                                            <h4>Instructions</h4>
                                                            <span v-html="question.instructions"></span>

                                                            <span v-if="question.instructions_image">
                                                                <img :src="'http://ucarecdn.com/'+question.instructions_image+'/nth/0/'" />
                                                            </span>
                                                        </div>

                                                        <span>
                                                            <span v-html="question.text"></span>
                                                        </span>

                                                        <span v-if="question.image">
                                                            <img :src="'http://ucarecdn.com/'+question.image+'/'" height="200" />
                                                        </span>

                                                        <p>
                                                            <!--Begin Option Looping -->
                                                            <span class="an-custom-radiobox blocked" v-for="(option,index) in question.options">

                                                                <input v-model="answer.optionIndex" type="radio" name="name2" :id='"option"+index' :value="String(index)" :checked="answer.optionIndex == index" >
                                                                <label v-if="option.text" :for='"option"+index'>
                                                                    {{option.text}}

                                                                    <span v-if="option.image" class="row">
                                                                         <img v-bind:src="'http://ucarecdn.com/'+option.image+'/'" width=200 />
                                                                     </span>

                                                                </label>
                                                                <label v-if="!option.text && option.image" :for='"option"+index'>

                                                                    <img v-bind:src="'http://ucarecdn.com/'+option.image+'/'" width=200 />

                                                                </label>
                                                            </span>
                                                            <!--End Option Looping -->
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <!--<div class="an-info-content notifications-info ps-container ps-theme-default"-->
                                                <!--data-ps-id="cc605463-842a-4a3f-e973-14c5fb02313f"-->
                                                <!--style="width: 300px;">-->
                                                <!--<div class="an-info-single unread favorite open"><a href="#"><span-->
                                                <!--class="user-img"-->
                                                <!--style="background-image: url('/assets/img/users/user1.jpeg');"></span>-->
                                                <!--<div class="info-content"><h5 class="user-name">-->
                                                <!--&lt;!&ndash;{{Auth::user()->name }}&ndash;&gt;-->
                                                <!--</h5>-->
                                                <!--<p class="content">Profile</p><span class="info-time"><i-->
                                                <!--class="icon-clock"></i>&lt;!&ndash; react-text: 420 &ndash;&gt;15:28-->
                                                <!--&lt;!&ndash; /react-text &ndash;&gt;</span></div>-->
                                                <!--</a></div>-->

                                                <!--</div> -->
                                                <div class="row">
                                                    <span class="" style="font-size: 17px;">
                                                            <i class="icon-clock"></i>
                                                            <span id="countdown">
                                                                {{timer }}
                                                                {{ clock }}
                                                            </span>
                                                    </span>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="an-info-single unread"><a href="#"><span
                                                                class="icon-container"
                                                                :style="responseBlockClass('answered')">
                                                            {{ getCountOfAnswerTypes(['answered']) }}
                                                        </span>
                                                            <div class="info-content"><h5 class="user-name">
                                                                Answered
                                                            </h5></div>
                                                        </a></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="an-info-single unread"><a href="#"><span
                                                                class="icon-container"
                                                                :style="responseBlockClass('unAnswered')">
                                                            {{ getCountOfAnswerTypes(['unAnswered']) }}
                                                        </span>
                                                            <div class="info-content"><h5 class="user-name">
                                                                Not Answered
                                                            </h5></div>
                                                        </a></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="an-info-single unread"><a href="#"><span
                                                                class="icon-container"
                                                                :style="responseBlockClass()">
                                                                 {{  questions.length - getCountOfAnswerTypes(['answered', 'unAnswered', 'markefForReview', 'answeredMarkedForReview' ]) }}
                                                        </span>
                                                            <div class="info-content"><h5 class="user-name">
                                                                Not Visited
                                                            </h5></div>
                                                        </a></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="an-info-single unread"><a href="#"><span
                                                                class="icon-container"
                                                                :style="responseBlockClass('markedForReview')">
                                                             {{ getCountOfAnswerTypes(['markedForReview']) }}
                                                        </span>
                                                            <div class="info-content"><h5 class="user-name">
                                                                Marked for Review
                                                            </h5></div>
                                                        </a></div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="an-info-single unread"><a href="#"><span
                                                                class="icon-container"
                                                                :style="responseBlockClass('answeredMarkedForReview')">
                                                             {{ getCountOfAnswerTypes(['answeredMarkedForReview']) }}
                                                        </span>
                                                            <div class="info-content"><h5 class="user-name">
                                                                Answered &amp; Marked for Review</h5></div>
                                                        </a></div>
                                                    </div>
                                                </div>

                                                <!-- Questions Block -->
                                                <div class="an-single-component with-shadow"
                                                     style="border-left: 1px solid rgb(218, 218, 218);">
                                                    <div class="">
                                                        <div class="an-small-doc-blcok"><h4>All Questions in this Section</h4></div>
                                                    </div>
                                                    <div class="an-component-body">
                                                        <div class="an-helper-block"><h6>Choose a question</h6>
                                                            <div class="row" style="height: 50px;">

                                                                <div v-for="(question, index ) in questions" class="col-sm-2 m-t-30">
                                                                    <div class="an-info-single unread">
                                                                        <a href="#" @click="setQuestionIndex(question.number-1)">
                                                                            <span class="icon-container" :style="responseBlockClass(question.number-1)">

                                                                            {{ question.number }}
                                                                    </span>
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div><!-- react-text: 481 --> <!-- /react-text -->
                                                </div>


                                                <!--{{answers}}-->
                                                <!--{{getSectionsWithQuestions}}-->

                                                <div class="col-sm-12 m-t-30">
                                                    <div class="an-helper-block">
                                                        <button class="an-btn an-btn-success pull-right disabled">Finish</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12" style="position:fixed;bottom:0;z-index:999; background: #fff; border-top:1px solid #000">

                        <div class="col-sm-8 col-xs-12">

                            <div class="an-helper-block">

                                <button class="an-btn an-btn-primary" v-on:click="dispatchAnswerWithType( answer.optionIndex? 'answeredMarkedForReview': 'markedForReview' );">
                                    Mark for Review & Next
                                </button>

                                <button class="an-btn an-btn-danger" @click="clearResponse()">Clear Response</button>

                                <button class="an-btn an-btn-transparent pull-left" @click="setQuestionIndex( questionIndex >0? questionIndex-1 : 0 )">  <i class="fa fa-chevron-left"></i> Previous
                                </button>

                                <button class="an-btn an-btn-success pull-right" @click="updateAnswer( answer.optionIndex? 'answered' : 'unAnswered' )">Save & Next
                                </button>

                                <!-- Logout Form -->
                                <div class="hidden">

                                    <form id="logoutForm" action="/logout" method="POST">
                                        <input type="hidden" name="_token" id="csrf_field" value="" >
                                    </form>

                                </div>



                            </div>

                        </div>



                    </div>



                </div>




            </div>



        </div>


    </div>
</template>


<style type="text/css">

    .m-t-10{
        margin-top: 10px !important;
    }

    .m-t-20{
        margin-top: 20px !important;
    }

    .m-t-30{
        margin-top: 30px !important;
    }

    .cleared input[type="radio"]:checked + label:before{
        content: '';
        color: #fff
    }
    .an-custom-checkbox label:before{
        color: #000;
    }
    .sweet-alert{
        width: 620px
    }
</style>


<!--<script type="text/javascript" src="http://www.hostmath.com/Math/MathJax.js?config=OK"></script>-->

<script type="text/babel">
    import examResultTable from '../store/examResultTable'
    export default{
        data(){
            return{
                acceptImportantInstructions: false, answerClasses : [], usr: null, minutesToBeAdded: 0, clock: null,
            }
        },

        props: ['examobject','now','instructions','sections', 'user'],

        components:{
            instructions : require('./Instructions.vue')
        },

        mounted(){

            this.usr = JSON.parse(this.user)
            this.$store.dispatch('initExam', { exam: this.exam, sections: this.sections, user: this.usr });
            // Comment the below section after finished debugging

            setTimeout(()=>{
                let userData = localStorage.getItem(this.exam.id)
                if(userData){
                    userData = JSON.parse(userData)
//                    if(userData.answers)
//                        swal('Continue taking your exam', 'We have detected that you were already making progress with this exam! Your marked answers and reviews will now be restored', '')

                    this.$store.dispatch('acceptExamInstructions', true )
                    this.$store.dispatch('acceptImportantInstructions', true )
                    this.$store.dispatch('updateCurrentQuestion', 0 )
                    this.initAnswerFromLocalStorage()
                }
//

            },1000)

            this.setQuestionIndex(0)

//            if(this.currentSection)
//                this.minutesToBeAdded = this.currentSection.time

            setTimeout(() => {
//                window.swal({
//                    html: true,
//                    title: '',
//                    text: examResultTable(this.$store.state.exam ),
//                    timer: 100000
//                }, isConfirm => {
//
//
//                })
            }, 1000)

//            this.timer

        },

        computed:{

            exam(){
                return JSON.parse( this.examobject )
            },

            finalResult(){
                return this.$store.getters.finalResult
            },

            currentSection(){
                return this.$store.getters.currentSection
            },

            acceptedExamInstructions(){
                return this.$store.getters.checkIfExamInstructionsAccepted
            },

            acceptedImportantInstructions(){
                return this.$store.getters.checkIfImportantInstructionsAccepted
            },

            question(){ // currentQuestion computed function
                window.scrollTo(0,0,);
                setTimeout(()=>{

                    if(!this.answer || !this.answer.optionIndex || ['unAnswered', undefined, false, null ].includes(this.answer.optionIndex) )
                        this.dispatchAnswerWithType( 'unAnswered' )
                },300);

                return this.$store.getters.currentQuestion || {}
            },

            questions(){
                return _.filter(this.$store.getters.getQuestions, question => {

                    return _.findIndex( this.getSectionsWithQuestions, section => (section.section_id == question.section_id) && section.active ) > -1
                } )
            },

            allQuestionsInThisExam() {
                return this.$store.getters.getQuestions
//              return   _.chain(this.getSectionsWithQuestions).map(section => section.questions_in_this_section).flattenDeep().map(que => que && que.question).flattenDeep().value()
            },
            questionIndex(){
                return this.$store.getters.currentQuestionIndex || 0
            },
            getSectionsWithQuestions(){
                return this.$store.getters.getSectionsWithQuestions
            },
            answers(){
                return this.$store.getters.getAnswers
            },
            answer(){
                return this.$store.getters.getAnswer(this.questionIndex)
            },

            totalQuestionsOfExam(){
                return _.reduce(this.getSectionsWithQuestions, ( num, section )=>{

                    return num+ +this.questionsInThisSection(section).length;

                } , 0 )
            },

            totalMarksOfExam(){
                return _.reduce(this.getSectionsWithQuestions, ( num, section )=>{

                    return num+ this.exam.marks_per_question * +this.questionsInThisSection(section).length;

                } , 0 )
            },

            sectionDuration(){
                return _.reduce(this.getSectionsWithQuestions, ( num, section )=>{

                    return num+ parseInt(section.time);

                } , 0 )
            },

            totalDurationOfExam(){
                let time =  _.reduce(this.getSectionsWithQuestions, ( num, section )=>{
                    return num+ +(section.time);

                } , 0 )

                return _.isNaN(time) ? this.exam.duration : time
            },

            timer(){
                if(! this.checkIfImportantInstructionsAccepted )
                    return;

                let startTimeOfUser = localStorage.getItem(this.exam.id), UserData = startTimeOfUser? JSON.parse(startTimeOfUser): {}
                if(startTimeOfUser && JSON.parse(startTimeOfUser))
                    startTimeOfUser = JSON.parse(startTimeOfUser).starttime


                this.minutesToBeAdded = this.exam.duration || (this.minutesToBeAdded + +this.currentSection.time);
                console.log('this.minutesToBeAdded', UserData.timeElapsed)

                var endTime = new Date( new Date(startTimeOfUser).getTime()+ (this.minutesToBeAdded * 60 * 1000 ) ).getTime();

                if(UserData.timeElapsed)
                    endTime = new Date( new Date().getTime()+ this.minutesToBeAdded*60*1000 - UserData.timeElapsed*1000 ).getTime();

                // $('#csrf_field').val( document.head.querySelector('meta[name="csrf-token"]').content )
                $('#csrf_field').val( window.Laravel.csrfToken );

                setTimeout(() => {

                    var x = setInterval( () => {

                        var distance = endTime - new Date().getTime();

                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        this.clock = hours+ ":" + minutes + ":" + (String(seconds).length == 1 ? '0'+seconds : seconds );
                        if (distance <= 1) {
                            clearInterval(x);
                            this.updateSection( this.exam.duration? 999999: null ) // if this is an open-section exam, then make the exam deliberately stop without proceeding
                            this.clock = "";
                            // this.$store.dispatch('logout')
                            // $('#logoutForm').submit()
                        }

                        this.$store.dispatch('incrementTimeElapsed')
                    }, 1000);

                }, 300)
            },

            ifExamIsCompleted() {

                return this.$store.getters.ifExamIsCompleted
            }

        },

        methods: {

            questionsInThisSection(section){
                return _.chain(section.questions_in_this_section).map(q =>  q.question).flattenDeep().value()
            },

            questionIdsInSection(sectionId){
                let questions =  _.filter(this.allQuestionsInThisExam, s => +s.section_id == +sectionId )
                return _.map(questions, q => q.question_id )
            },

            countOfAnswers(sectionId, types=[], exclude=false){
                let questionIds =  this.questionIdsInSection(sectionId)

                return _.reduce(_.filter(this.answers, ans => questionIds.includes(ans.question_id) ), (val,answer) => {

                    if(exclude? ! types.includes(answer.type) : types.includes(answer.type)){
                        return val+1
                    }
                    return val

                }, 0 )
            },

            unAttemptedAnswers(sectionId){
                let questions =  _.get(_.find(this.getSectionsWithQuestions, s => s.section_id == sectionId ), 'questions')

                return _.map(questions, q => q.question_id )
            },

            initAnswerFromLocalStorage(){
                this.$store.dispatch( 'initAnswerFromLocalStorage')
            },
            // Because we are using this same store action in multiple methods, its better to place it a single method

            dispatchAnswerWithType(type){

                this.$store.dispatch('updateAnswer' , { questionIndex: this.questionIndex, optionIndex: type !='unAnswered'? this.answer.optionIndex: false, type });

                if(['answeredMarkedForReview', 'markedForReview','answered'].includes(type))
                    this.setQuestionIndex(this.questionIndex+1)

            },

            updateAnswer(type='answered'){

                this.dispatchAnswerWithType(this.answer.optionIndex? type : 'unAnswered')
//                this.$store.dispatch('updateCurrentQuestion', this.questionIndex+1 )

//                window.scrollTo(0,0)
                if(type=='unAnswered')
                    this.setQuestionIndex(this.questionIndex+1)

            },

            updateSection(sectionIndex){

                // lets proceed to the next section. This will increase the active section index by 1, by mutator in the store
                this.$store.dispatch('updateSection', {sectionIndex})

            },

            colors(index){

                return '#38A9EB';

            },

            setQuestionIndex(index){
                let lastQuestionInThisSection = _.last(this.questions)
                if( lastQuestionInThisSection && lastQuestionInThisSection.number == index && _.last(this.allQuestionsInThisExam ).number == index )
                    return swal('You have reached the end of the question paper. Please wait till the countdown stops')

                this.$store.dispatch('updateCurrentQuestion', index )
            },

            // arg type should be of Array type
            getCountOfAnswerTypes(type){

                let questionIdsInThisSection = this.questions.map(q => q.question_id),
                    answersForThisSection = this.answers.filter(answer =>  questionIdsInThisSection.includes(answer.question_id) )
                return _.filter( answersForThisSection, ans => type.includes(ans.type) ).length

            },

            getAnswerByQuestionIndex(questionIndex){

                return _.find(this.answers, ans => ans.questionIndex == questionIndex )
            },

            responseBlockClass(questionIndex){

                let answer  = this.getAnswerByQuestionIndex(questionIndex),
                    bgImageUrl = '/assets/img/questions-sprite.png'

                switch(answer? answer.type: questionIndex ){
                    case 'answered':
                        return  "background-image: url("+bgImageUrl+"); background-position: -5px -50px; border-radius: 0px;"
                        break;
                    case 'unAnswered':
                        return  "background-image: url("+bgImageUrl+"); background-position: -39px -50px; border-radius: 0px;";
                        break;
                    case 'markedForReview':
                        return "background-image: url("+bgImageUrl+"); background-position: -71px -50px; border-radius: 0px;"
                        break;

                    case 'answeredMarkedForReview':
                        return "background-image: url("+bgImageUrl+"); background-position: -168px -55px; border-radius: 0px;"
                        break;
                    default:
                        // not visited
                        return "background-image: url("+bgImageUrl+"); background-position: -104px -50px; border-radius: 0px; color: rgb(0, 0, 0);"
                }

            },

            checkIfExamInstructionsAccepted(){

                this.$store.dispatch('acceptExamInstructions', true );

            },

            goBackToExamInstructions(){

                this.$store.dispatch('acceptExamInstructions', false );
                setTimeout(() => {
                    window.scrollTo(0,0)
                },200)

            },

            checkIfImportantInstructionsAccepted(){

                if(! this.acceptImportantInstructions)
                    swal('Please accept our terms and conditions before proceeding!');
                else {

                    this.$store.dispatch('acceptImportantInstructions', true );
                    this.$store.dispatch('updateCurrentQuestion', 0 );
                    swal('Exam started')
                }

            },

            clearResponse(){

                $('input[type="radio"]:checked').prop('checked', false );
                this.dispatchAnswerWithType('unAnswered')

            },


        }
    }
</script>

<style type="text/css">
    .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td{
        color: #000;
    }

    .strong{
        font-weight: 700;
    }
</style>
