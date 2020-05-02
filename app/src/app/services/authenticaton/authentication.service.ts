import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable, Subject, BehaviorSubject } from 'rxjs';
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
    private constantService: ConstantService
  ) { }

  isLogged()
  {
    this.storageService.get(this.constantService.AUTH).then(
      res => {
        this.userData$.next(res);
      }
    )
  }
}
