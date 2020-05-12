import { Pipe, PipeTransform } from '@angular/core';
import { Tags } from '../models/tags.model'
@Pipe({
  name: 'tagsFilter'
})
export class TagsPipe implements PipeTransform {

  transform(tags: Tags[], text: string): Tags[] {
    if( text.length === 0) { return tags; }

    text = text.toLocaleLowerCase();

    return tags.filter( data => {
      return data.TagName.toLocaleLowerCase().includes(text);
    });
  }
}
