import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { StorageService } from '../../services/storage/storage.service';
import { ConstantService } from '../../services/constant/constant.service';
@Injectable({
  providedIn: 'root'
})
export class PublicGuard implements CanActivate {
  constructor(public storageService: StorageService, public router: Router, private constantService:ConstantService){}

  canActivate(): Promise<boolean> {
    return new Promise(resolve => {
      this.storageService.get(this.constantService.AUTH).then(res => {
        if(res) {
          this.router.navigate(['board/home'])
          resolve(false)
        }
        else
        {
          resolve(true)
        }
      }).catch(err => {
        resolve(true)
      })
    })
  }
}
