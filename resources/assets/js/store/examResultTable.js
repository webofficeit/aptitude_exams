import _ from 'lodash'
import moment from 'moment'
import swal from 'sweetalert'
let state;

function questionIdsInSection(sectionId){
    let questions =  _.filter(state.questions, s => +s.section_id == +sectionId )
    return _.map(questions, q => q.question_id )
}

function  countOfAnswers(sectionId, types=[], exclude=false){
    let questionIds =  questionIdsInSection(sectionId)
    return _.reduce(_.filter(state.answers, ans => questionIds.includes(ans.question_id) ), (val,answer) => {

        if(exclude? ! types.includes(answer.type) : types.includes(answer.type)){
            return val+1
        }
        return val

    }, 0 )
}

function countOfCorrectIncorrectAnswers(sectionId, correct=true){
    let questionIds =  questionIdsInSection(sectionId)
    let count = _.reduce(_.filter(state.answers, ans => questionIds.includes(ans.question_id) ), (val,answer) => {

        if(answer.optionIndex )
            if(correct && +answer.optionIndex == +answer.optionIndexOfAnswer)
                return val+1;
            else if(!correct && +answer.optionIndex !== +answer.optionIndexOfAnswer)
                return val+1;

        return val

    }, 0 )

    return count
}

function calculateMarks(sectionId) {
    let questionIds =  questionIdsInSection(sectionId);
    let count = _.reduce(_.filter(state.answers, ans => questionIds.includes(ans.question_id) ), (val,answer) => {

        if(answer.optionIndex)
            if(+answer.optionIndex == +answer.optionIndexOfAnswer)
                return val+state.exam.marks_per_question;
            else return val-state.exam.negative_marks;

        return val
    }, 0);

    return count* state.exam.marks_per_question
}

export default function showExamResultTable(st){

    state = st;

    const { exam, user, sectionsWithQuestions, currentSection, questions,ections,nswers,urrentQuestionIndex, currentQuestion, acceptedExamInstructions, acceptedImportantInstructions, start_date_time, duration, now, remainingTime, examStartDateTime, answerModelInServer, completed, answerKeyUrl } = state;

    let result = `<table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Section</th>
                                                            <th>Attempted</th>
                                                            <th>Unattempted</th>
                                                            <th>Correct</th>
                                                            <th>Wrong</th>
                                                            <th>Marks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    ${_.map(sectionsWithQuestions, section => {
        return `<tr>
                                                        <td>${section.section_name}</td>
                                                        <td>${countOfAnswers(section.section_id, ['answered', 'answeredMarkedForReview'])}</td>
                                                        <td>${+questionIdsInSection(section.section_id).length - +countOfAnswers(section.section_id, ['answered', 'answeredMarkedForReview'])}</td>
                                                        <td>${ countOfCorrectIncorrectAnswers(section.section_id) }</td>
                                                        <td>${ countOfCorrectIncorrectAnswers(section.section_id, false) }</td>
                                                        <td>${ calculateMarks(section.section_id) }</td>
                                                        
                                                            </tr>`
    }).join('')}
                                                    
                                                    <tr class="strong">
                                                        <td>Total</td>
                                                        <td>
                                                            ${_.reduce(sectionsWithQuestions, (val, section)=>{
                                                                
                                                                return val+ +countOfAnswers(section.section_id, ['answered', 'answeredMarkedForReview'])
                                                                
                                                            }, 0 )}
                                                        </td>
                                                        <td>
                                                            ${_.reduce(sectionsWithQuestions, (val, section)=>{
                                                                
                                                                return val+ +questionIdsInSection(section.section_id).length - +countOfAnswers(section.section_id, ['answered', 'answeredMarkedForReview'])
                                                                
                                                            }, 0 )}
                                                        </td>
                                                        <td>
                                                            ${_.reduce(sectionsWithQuestions, (val, section)=>{
                                                                
                                                                return val+ countOfCorrectIncorrectAnswers(section.section_id)
                                                                
                                                            }, 0 )}
                                                        </td>
                                                        <td>
                                                            ${_.reduce(sectionsWithQuestions, (val, section)=>{
                                                                
                                                                return val+ countOfCorrectIncorrectAnswers(section.section_id, false)
                                                                
                                                            }, 0 )}
                                                        </td>
                                                        <td style="color:green">
                                                            ${_.reduce(sectionsWithQuestions, (val, section)=>{
                                                                
                                                                return val+ calculateMarks(section.section_id)
                                                                
                                                            }, 0 )}
                                                        </td>
                                                    </tr>
                                                    
                                                    </tbody>
                                                </table>`


    if(answerKeyUrl)
        result += `<div class="col-sm-offset-5 col-sm-2"> 
    <a class="an-btn an-btn-success" href="${answerKeyUrl}" target="_blank">View ANSWER KEY</a>
  </div>`

    return result
}
