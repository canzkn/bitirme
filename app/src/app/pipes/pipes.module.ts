import { NgModule } from '@angular/core';
import { TagPipe } from './tag.pipe'



@NgModule({
  declarations: [TagPipe],
  exports: [
    TagPipe
  ]
})
export class PipesModule { }