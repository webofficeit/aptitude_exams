import Vue from 'vue'
import Vuex from 'vuex'
import _ from 'lodash'
import moment from 'moment'
import swal from 'sweetalert'
import examResultTable from './examResultTable'

Vue.use(Vuex)

window._ = _;

const state = { exam: false, user:false, sectionsWithQuestions: null, currentSection: null, questions: [], sections: [], answers: [], currentQuestionIndex: null, currentQuestion: null, acceptedExamInstructions: false, acceptedImportantInstructions:false, start_date_time: false, duration: false, now: false, remainingTime : false , examStartDateTime: false, answerModelInServer: false, completed: false, answerKeyUrl: '', finalResult: '', timeElapsed: false }


const getters = {

    getQuestions: state => _.compact(state.questions),

    getSectionsWithQuestions: state => _.compact(state.sectionsWithQuestions),

    currentSection: state => state.currentSection,

    getQuestionByIndex : (state, getters) => questionIndex => state.questions[questionIndex],

    currentQuestion : (state, getters) => state.currentQuestion,

    currentQuestionIndex : (state, getters) => state.currentQuestionIndex,

    getAnswers : state => state.answers,

    getAnswer : state => questionIndex =>  _.find(state.answers, ans => ans.questionIndex == questionIndex ),

    checkIfExamInstructionsAccepted: state => state.acceptedExamInstructions,

    checkIfImportantInstructionsAccepted: state => state.acceptedImportantInstructions,

    getDuration : state => state.duration,

    ifExamIsCompleted: state => state.completed,

    finalResult: state => state.finalResult,

}

const mutations = {

    INIT_EXAM : (state, {exam, sections, user } ) => {

        // lets check whether candidate has already started taking the exam
        //. pending

        state.exam = exam;
        state.user = user;
        state.answerKeyUrl = _.get(exam,'question_paper.answer_key_url');

        let questionPaperQuestions = _.get(exam,'question_paper.questions');

        if(questionPaperQuestions){

            let sectionsWithQuestions = JSON.parse(questionPaperQuestions),
                sectionModels = JSON.parse(sections);

            sectionsWithQuestions = sectionsWithQuestions.map( (section, index) => {

                section['index'] = index
                section['section_name'] = _.get(_.find(sectionModels, s => s.id == section.section_id ), 'name')
                section['section_instructions'] = _.get(_.find(sectionModels, s => s.id == section.section_id ), 'default_instructions')
                if(index==0){
                    section['active'] = true
                    state.currentSection = section;
                }
                else section['active'] = false

                return section;
            } );
            state.sectionsWithQuestions = sectionsWithQuestions;

            // console.warn('INIT_EXAM sectionsWithQuestions', sectionsWithQuestions )

            let questionIds = _.uniq(_.flattenDeep(sectionsWithQuestions.map(section => {

                    return section.questions.map(q =>  q.question_id )

                }))
            );

            axios.get('/api/questions',{ params: {questionIds} }).then(resp=> {

                this.a.dispatch('setQuestionsFromServer', resp.data )
            })

        }

        // Lets check how much time has elapsed for this user for this exam
        const UserData = localStorage.getItem(state.exam.id)?  JSON.parse(localStorage.getItem(state.exam.id)): {};

        state.now = moment(state.exam.now);
        const now = state.now;
        const now2 = state.now;
        state.start_date_time = UserData.timeElapsed? now.subtract(UserData.timeElapsed*1000): moment(state.exam.start_date_time);
        state.duration = state.exam.duration*60; // converting minutes into seconds

        // moment returns this value in milliseconds
        state.remainingTime = moment(state.start_date_time.add(state.duration*1000)).diff(state.now);
        state.passedTIme = UserData.timeElapsed || now2.diff(state.start_date_time) ;

        console.log(UserData.timeElapsed, state.now.toString(), state.start_date_time.toString(), state.remainingTime, state.passedTIme)

    },

    UPDATE_ANSWER: (state, { questionIndex, optionIndex, type } ) => {

        let answerIndex = _.findIndex(state.answers, ans => ans.questionIndex == questionIndex),
            question = _.find(state.questions,(q, index) => index == questionIndex),
            optionIndexOfAnswer = _.findIndex(question && question.options, option => option.answer )

        const {question_id} = question || {}

        if(answerIndex == -1)
            state.answers.push({  questionIndex, question_id, optionIndex, optionIndexOfAnswer, type });
        else Vue.set( state.answers, answerIndex, {  questionIndex, question_id, optionIndex, optionIndexOfAnswer, type }  )

        //Update the localStorage
        let userData = localStorage.getItem(state.exam.id)

        if(userData){
            userData = ( userData && JSON.parse(userData) )? JSON.parse(userData): {}
            userData['answers'] = state.answers
            localStorage.setItem(state.exam.id, JSON.stringify(userData))
        }

        // Move onto the next question after above operations
        // state.currentQuestionIndex = state.currentQuestionIndex+1

    },

    UPDATE_CURRENT_QUESTION : (state, currentQuestionIndex) => {

        if(state.questions[currentQuestionIndex]){
            state.currentQuestionIndex = currentQuestionIndex
            state.currentQuestion = state.questions[currentQuestionIndex]
        }

        // In case, if this exam is an open-section exam, we need to highlight the activated section as well.
        state.sectionsWithQuestions = state.sectionsWithQuestions.map(section => {
            section['active'] = state.currentQuestion && state.currentQuestion.section_id == section.section_id
            return section;
        })

    },

    ACCEPT_EXAM_INSTRUCTIONS: (state, accepted) => { state.acceptedExamInstructions = accepted },

    ACCEPT_IMPORTANT_INSTRUCTIONS: (state, accepted ) => {

        state.acceptedImportantInstructions = accepted;

        // lets store the start time of the exam taken by the current user
        if(! localStorage.getItem(state.exam.id))
            localStorage.setItem(state.exam.id, JSON.stringify({ user_id: state.user.id, starttime: new Date() }) );

        axios.post('/api/answers',{ exam_id : state.exam.id, user_id: state.user.id } ).then(resp=> {

            state.start_date_time = resp.data.start_date_time.date;
            state.answerModelInServer = resp.data;
            // this.a.dispatch('setQuestionsFromServer', resp.data )
        })

    },

    SET_QUESTIONS_FROM_SERVER: (state, questions) => {

        let processedQuestions = _.map(questions, q => {

            q.question = JSON.parse(q.question)
            return q;

        });

        state.sectionsWithQuestions = state.sectionsWithQuestions.map( (section, sectionIndex) => {

            section['questions_in_this_section'] = _.filter( processedQuestions, q => parseInt(q.section_category_id) == parseInt(section.section_id) )
            return section;

        });

        let questionsToState = _.flattenDeep( _.map(state.sectionsWithQuestions, section => {


            return _.map(section.questions_in_this_section, qu => {

                return _.map(qu.question, (q,qIndex) => {

                    q['section_id'] = section.section_id;
                    q['question_id'] = qu.id;
                    q['section_name'] = section.section_name;
                    q['instructions'] = qu.instructions;
                    q['instructions_image'] = qu.instructions_image;
                    q['collapsed'] = qIndex == 0? false: true;

                    return q
                });

                return qu;
            })


        } ) );

        questionsToState = _.map(questionsToState, (q,index) => {

            q['number'] = index+1;
            return q
        })

        state.questions = questionsToState;
        // console.warn( 'questions', state.questions )
        // console.warn( 'sectionsWithQuestions', state.sectionsWithQuestions )

    },

    UPDATE_SECTION: (state, {sectionIndex}) => {

        let indexOfCurrentActiveSection = _.findIndex(state.sectionsWithQuestions, section => section.active )

        state.sectionsWithQuestions = _.map(state.sectionsWithQuestions, section => {
            section.active = false
            return section;
        } );

        let indexToBeUpdated = +sectionIndex >=0 ? +sectionIndex : ( indexOfCurrentActiveSection > -1 ? (indexOfCurrentActiveSection+1) : 0 );

        // if we have reached the end of all sections, then we need to alert the user and end the exam
        if(! state.sectionsWithQuestions[ indexToBeUpdated]){
            state.completed = true;

            swal({
                title: 'Please wait...',
                text: '',
                timer: 10000,
                showConfirmButton: false
            })
            let wrongAnswers = 0, correctAnswers = 0;

            let totalMarks = _.reduce( state.answers, (val, answer )=> {
                if(answer.type== 'answered' || answer.type == 'answeredMarkedForReview' ){

                    if(answer.optionIndex )
                        if(+answer.optionIndex == +answer.optionIndexOfAnswer)
                            correctAnswers++;
                        else wrongAnswers++;

                    return +val + ( answer.optionIndex ? (+answer.optionIndex == +answer.optionIndexOfAnswer ? +state.exam.marks_per_question : - +state.exam.negative_marks ) : 0 );
                }
                else return +val ;
            }, 0)

            axios.post('/api/answer/'+state.answerModelInServer.id, { exam_id: state.exam.id, user_id: state.user.id, answers: JSON.stringify(state.answers), finished: true, total_marks: totalMarks, wrong_answers: wrongAnswers, correct_answers: correctAnswers, exam_summary_html: examResultTable(state) } ).then(res => {
                // console.log(res.data)
                if(res.data){
                    localStorage.removeItem(state.exam.id)

                    state.finalResult= '<h2>Time over! '+ 'Your score is '+totalMarks + ' out of '+ state.exam.marks_per_question*state.questions.length+'</h2></br></br>';

                    state.finalResult += examResultTable(state);


                    // return swal({
                    //     html: true,
                    //     title: 'Time over! '+ 'Your score is '+totalMarks + ' out of '+ state.exam.marks_per_question*state.questions.length ,
                    //     text: examResultTable(state),
                    //     type: 'success',
                    //     // timer: 100000,
                    //     showConfirmButton: true,
                    //     buttons:{
                    //         ok: {
                    //             value: 'confirm'
                    //         }
                    //     }
                    // }, isConfirm => {
                    //
                    //     // if(isConfirm)
                    //     //     location.href = '/';
                    //
                    // })
                }
                else return swal('Network issue', 'There seems to be a problem with the network! Please don\'t close this browser window. Refresh this page when internet connection is available!', 'error')

            }).catch(e => {

                console.log('e', e)
                swal('Network issue', 'There seems to be a problem with the network! Please don\'t close this browser window. Refresh this page when internet connection is available!', 'error')
            } )


        }

        let sectionToBeUpdated = state.sectionsWithQuestions[ indexToBeUpdated]

        if(sectionToBeUpdated){
            sectionToBeUpdated['active'] = true;
            state.currentSection = sectionToBeUpdated;
            this.a.dispatch('updateCurrentQuestion', state.currentSection.questions_in_this_section[0].question[0].number-1 )
        }

    },

    INIT_ANSWERS_FROM_LOCAL_STORAGE: state => {

        let userData = localStorage.getItem(state.exam.id)

        if(userData ) {
            userData = JSON.parse(userData)
            if(userData.answers)
                state.answers = userData.answers
        }

    },

    INCREMENT_TIME_ELAPSED: state => {

        const UserData = JSON.parse(localStorage.getItem(state.exam.id));
        UserData['timeElapsed'] = state.timeElapsed || UserData.timeElapsed || 0
        localStorage.setItem(state.exam.id, JSON.stringify(UserData));
        state.timeElapsed = UserData.timeElapsed + 1;
    }

};


const actions = {

    initExam : ( {commit},  ...props ) => commit('INIT_EXAM', ...props ),

    setQuestionsFromServer: ({commit}, questions ) => commit('SET_QUESTIONS_FROM_SERVER', questions),

    updateAnswer : ( {commit}, { questionIndex, optionIndex, type } ) => commit('UPDATE_ANSWER', { questionIndex, optionIndex, type }  ),

    updateCurrentQuestion : ({commit}, currentQuestionIndex ) => commit('UPDATE_CURRENT_QUESTION', currentQuestionIndex ),

    acceptExamInstructions : ({commit}, accepted ) => commit('ACCEPT_EXAM_INSTRUCTIONS', accepted ),

    acceptImportantInstructions : ({commit}, accepted ) => commit('ACCEPT_IMPORTANT_INSTRUCTIONS', accepted ),

    updateSection : ({commit}, {sectionIndex} ) => commit('UPDATE_SECTION' , { sectionIndex } ),

    logout: ({commit}) => {

        Vue.http.post('/logout', response => response.json()).then(response => {

            swal('You have been logged out')

        })


    },

    initAnswerFromLocalStorage: ({commit}) => commit('INIT_ANSWERS_FROM_LOCAL_STORAGE'),

    incrementTimeElapsed: ({commit}) => commit('INCREMENT_TIME_ELAPSED'),
};


const exam = {
    state,
    getters,
    mutations,
    actions
};


export default new Vuex.Store({
    modules:{
        exam
    }
});
