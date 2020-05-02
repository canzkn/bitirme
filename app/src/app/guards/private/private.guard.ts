import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { StorageService } from '../../services/storage/storage.service';
import { ConstantService } from '../../services/constant/constant.service';

@Injectable({
  providedIn: 'root'
})
export class PrivateGuard implements CanActivate {
  constructor(public storageService: StorageService, public router: Router, private constantService:ConstantService){}

  canActivate(): Promise<boolean> {
    return new Promise(resolve => {
      this.storageService.get(this.constantService.AUTH).then(res => {
        if(res) {
          resolve(true)
        }
        else
        {
          this.router.navigate(['login'])
          resolve(false)
        }
      }).catch(err => {
        resolve(false)
      })
    })
  }
}
