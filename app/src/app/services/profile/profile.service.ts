import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable, BehaviorSubject } from 'rxjs';
import { AuthenticationService } from '../authenticaton/authentication.service';


@Injectable({
  providedIn: 'root'
})
export class ProfileService {

  activeProfile = new BehaviorSubject(null);

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
    private auth : AuthenticationService
  ) { }

  // Add Interest
  addInterest(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'profile/interest/add', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // get profile
  getProfile(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'users/get', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // get my profile
  getMyProfile(auth) : Observable<any>
  {
    return this.http.get(this.constantService.API_URL + 'profile', {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // update my profile
  updateMyProfile(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'profile/update', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Load Active Profile
  loadActiveProfile()
  {
    this.auth.userData$.subscribe(res => {
      this.getMyProfile(res.data).subscribe(response => {
        this.activeProfile.next(response)
      })
    })
  }
}
