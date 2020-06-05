import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable, BehaviorSubject } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { StorageService } from '../storage/storage.service';
import { ConstantService } from '../constant/constant.service'

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {

  userData$ = new BehaviorSubject<any>([]);

  constructor(
    private http: HttpClient,
    private storageService: StorageService,
    private router: Router,
    private constantService: ConstantService,
  ) { }

  // create user
  signup(postData: any): Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'register', postData);
  }

  // login user
  login(postData: any): Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'login', postData);
  }

  // logout user
  logout()
  {
    this.storageService.removeStorageItem(this.constantService.AUTH).then(res => {
      this.userData$.next('');
      this.router.navigate(['login']);
    });
  }

  // is user logged
  isLogged()
  {
    this.storageService.get(this.constantService.AUTH).then(
      res => {
        this.userData$.next(res);
      }
    )
  }
}
