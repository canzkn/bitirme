import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class QuestionService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Add Question
  addQuestion(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/add', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Add Answer to Question
  addAnswer(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/add_answer', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Numerical Operations on Question
  numericalOperations(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/numerical_operations', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Get Last Questions
  getLastQuestions(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/get_last', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  } 

  // Get All Questions
  getAllQuestions(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/get_all', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
  
  // Get Single Question
  getQuestion(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/get', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // mark solved answer
  markSolved(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/marksolved', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
